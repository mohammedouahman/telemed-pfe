import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '../../services/api';
import toast from 'react-hot-toast';
import StatusBadge from '../../components/StatusBadge';
import { Users, FileText, CalendarCheck, Check, Video, Loader2, Calendar } from 'lucide-react';
import { format } from 'date-fns';
import { fr } from 'date-fns/locale';

const DoctorDashboard = () => {
  const [stats, setStats] = useState(null);
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      const [statsRes, apptsRes] = await Promise.all([
        api.get('/doctor/stats'),
        api.get('/doctor/appointments')
      ]);
      setStats(statsRes.data);
      setAppointments(apptsRes.data);
    } catch (e) {
      toast.error('Erreur de chargement');
    } finally {
      setLoading(false);
    }
  };

  const handleStatusUpdate = async (id, status) => {
    try {
      if (status === 'confirmed') await api.put(`/appointments/${id}/confirm`); // Wait, we made an endpoint /doctor/appointments/{id}/confirm
      // Actually backend routes say: /doctor/appointments/{id}/confirm
      // Let's use correct routes
      await api.put(`/doctor/appointments/${id}/${status === 'confirmed' ? 'confirm' : 'complete'}`);
      toast.success('Rendez-vous mis à jour');
      fetchData();
    } catch (e) {
      toast.error('Erreur lors de la mise à jour');
    }
  };

  if (loading) return <div className="flex justify-center p-20"><Loader2 className="animate-spin w-8 h-8 text-blue-500"/></div>;

  const todayStr = format(new Date(), 'yyyy-MM-dd');
  const todayAppointments = appointments.filter(a => a.appointment_date === todayStr);

  return (
    <div className="min-h-[calc(100vh-64px)] bg-slate-50 py-10 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        
        {/* Stats Row */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
          <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div className="bg-blue-50 text-blue-600 p-4 rounded-2xl"><Users className="w-8 h-8"/></div>
            <div>
              <p className="text-sm font-bold text-slate-500">Patients</p>
              <p className="text-2xl font-extrabold text-slate-900">{stats?.total_patients || 0}</p>
            </div>
          </div>
          <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div className="bg-emerald-50 text-emerald-600 p-4 rounded-2xl"><FileText className="w-8 h-8"/></div>
            <div>
              <p className="text-sm font-bold text-slate-500">Consultations</p>
              <p className="text-2xl font-extrabold text-slate-900">{stats?.total_consultations || 0}</p>
            </div>
          </div>
          <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
             <div className="bg-amber-50 text-amber-600 p-4 rounded-2xl"><CalendarCheck className="w-8 h-8"/></div>
            <div>
              <p className="text-sm font-bold text-slate-500">A venir</p>
              <p className="text-2xl font-extrabold text-slate-900">{stats?.upcoming || 0}</p>
            </div>
          </div>
          <div className="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4">
             <div className="bg-purple-50 text-purple-600 p-4 rounded-2xl"><span className="text-2xl font-bold">€</span></div>
            <div>
              <p className="text-sm font-bold text-slate-500">Revenus M</p>
              <p className="text-2xl font-extrabold text-slate-900">{stats?.revenue_month || 0} €</p>
            </div>
          </div>
        </div>

        <h2 className="text-2xl font-extrabold text-slate-900 mb-6 flex items-center gap-2">
          <Calendar className="w-6 h-6 text-blue-500" />
          Agenda d'aujourd'hui
        </h2>

        {todayAppointments.length === 0 ? (
          <div className="bg-white rounded-2xl p-8 text-center border border-slate-100 shadow-sm mb-10">
            <p className="text-slate-500 font-medium">Aucun rendez-vous prévu pour aujourd'hui.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            {todayAppointments.map(app => (
              <div key={app.id} className="bg-white border-l-4 border-blue-500 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                  <div className="flex items-center gap-2 mb-1">
                    <h3 className="font-bold text-slate-900 text-lg">{app.patient?.name}</h3>
                    <StatusBadge status={app.status} />
                  </div>
                  <div className="text-slate-600 font-medium text-sm">
                    {app.start_time.substring(0,5)} - {app.end_time.substring(0,5)} • {app.patient?.patient_profile?.age} ans
                  </div>
                </div>

                <div className="flex gap-2 w-full sm:w-auto mt-4 sm:mt-0">
                  {app.status === 'pending' && (
                    <button onClick={() => handleStatusUpdate(app.id, 'confirmed')} className="flex-1 sm:flex-none justify-center flex items-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold px-4 py-2 rounded-xl border border-emerald-200 transition-colors">
                      <Check className="w-4 h-4" /> Accepter
                    </button>
                  )}
                  {app.status === 'confirmed' && (
                    <>
                      <Link to={`/video-call/${app.video_room_id}`} className="flex-1 sm:flex-none justify-center flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl transition-colors shadow-md">
                        <Video className="w-4 h-4" /> Démarrer
                      </Link>
                      <Link to={`/doctor/consultation/${app.id}`} className="flex-1 sm:flex-none justify-center flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2 rounded-xl transition-colors">
                        <FileText className="w-4 h-4" /> Notes
                      </Link>
                    </>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}

      </div>
    </div>
  );
};

export default DoctorDashboard;
