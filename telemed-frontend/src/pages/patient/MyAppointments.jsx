import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '../../services/api';
import toast from 'react-hot-toast';
import StatusBadge from '../../components/StatusBadge';
import { Calendar, Clock, Video, FileText, Ban, Loader2 } from 'lucide-react';
import { format, isToday } from 'date-fns';
import { fr } from 'date-fns/locale';

const MyAppointments = () => {
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchAppointments();
  }, []);

  const fetchAppointments = async () => {
    try {
      const res = await api.get('/appointments');
      setAppointments(res.data);
    } catch (e) {
      console.error(e);
      toast.error('Erreur lors du chargement des rendez-vous');
    } finally {
      setLoading(false);
    }
  };

  const handleCancel = async (id) => {
    if (!window.confirm('Voulez-vous vraiment annuler ce rendez-vous ?')) return;
    try {
      await api.put(`/appointments/${id}/cancel`);
      toast.success('Rendez-vous annulé');
      fetchAppointments();
    } catch (e) {
      toast.error('Erreur lors de l\'annulation');
    }
  };

  const downloadPrescription = async (appointment) => {
    try {
      toast.loading('Génération de l\'ordonnance...', {id: 'pdf'});
      // we need to find the prescription id first, for demo we just use the API endpoint that finds it or we call a new one
      // The prompt says /api/prescriptions/{id}/download but we only have appointment...
      // Let's assume the user goes to /api/prescriptions and downloads the first one matching
      const res = await api.get('/prescriptions');
      const pres = res.data.find(p => p.consultation?.appointment_id === appointment.id);
      
      if (!pres) {
        toast.error('Aucune ordonnance trouvée pour ce RDV', {id: 'pdf'});
        return;
      }

      // now download
      const pdfRes = await api.get(`/prescriptions/${pres.id}/download`, { responseType: 'blob' });
      const url = window.URL.createObjectURL(new Blob([pdfRes.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `ordonnance_${pres.id}.pdf`);
      document.body.appendChild(link);
      link.click();
      toast.success('Document téléchargé', {id: 'pdf'});
    } catch (e) {
      toast.error('Erreur de téléchargement', {id: 'pdf'});
    }
  };

  if (loading) return <div className="flex justify-center p-20"><Loader2 className="animate-spin w-8 h-8 text-blue-500"/></div>;

  return (
    <div className="min-h-[calc(100vh-64px)] bg-slate-50 py-10 px-4 sm:px-6 lg:px-8">
      <div className="max-w-5xl mx-auto">
        <h1 className="text-3xl font-extrabold text-slate-900 mb-8">Mes Rendez-vous</h1>
        
        {appointments.length === 0 ? (
          <div className="bg-white rounded-2xl p-12 text-center border border-slate-200">
            <Calendar className="w-12 h-12 text-slate-300 mx-auto mb-4" />
            <h3 className="text-lg font-bold text-slate-900">Aucun rendez-vous</h3>
            <p className="text-slate-500 mt-2 mb-6">Vous n'avez pas encore de consultations planifiées.</p>
            <Link to="/patient" className="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">Trouver un médecin</Link>
          </div>
        ) : (
          <div className="space-y-4">
            {appointments.map(app => (
              <div key={app.id} className="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 hover:shadow-md transition-shadow">
                
                <div className="flex items-center gap-6 w-full md:w-auto">
                  <div className="bg-blue-50 rounded-2xl p-4 text-center min-w-[100px] flex-shrink-0">
                    <div className="text-sm font-bold text-blue-500 uppercase">{format(new Date(app.appointment_date), 'MMM', {locale: fr})}</div>
                    <div className="text-3xl font-extrabold text-blue-700">{format(new Date(app.appointment_date), 'dd')}</div>
                  </div>
                  
                  <div>
                    <div className="flex items-center gap-2 mb-2">
                      <h3 className="font-bold text-slate-900 text-lg">{app.doctor?.name}</h3>
                      <StatusBadge status={app.status} />
                    </div>
                    <p className="text-blue-600 font-medium mb-2">{app.doctor?.doctor_profile?.specialty || 'Médecin'}</p>
                    <div className="flex items-center gap-4 text-sm text-slate-600 font-medium">
                      <span className="flex items-center gap-1.5"><Clock className="w-4 h-4 text-slate-400"/> {app.start_time.substring(0,5)} - {app.end_time.substring(0,5)}</span>
                    </div>
                  </div>
                </div>

                <div className="flex gap-3 w-full md:w-auto justify-end">
                  {app.status === 'confirmed' && (
                    <Link
                      to={`/video-call/${app.video_room_id}`}
                      className="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold transition-all"
                    >
                      <Video className="w-4 h-4" /> Rejoindre
                    </Link>
                  )}
                  {app.status === 'completed' && (
                    <button
                      onClick={() => downloadPrescription(app)}
                      className="flex items-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 px-5 py-2.5 rounded-xl font-bold transition-all border border-blue-200"
                    >
                      <FileText className="w-4 h-4" /> Ordonnance
                    </button>
                  )}
                  {(app.status === 'pending' || app.status === 'confirmed') && (
                    <button
                      onClick={() => handleCancel(app.id)}
                      className="flex items-center gap-2 bg-white hover:bg-red-50 text-slate-700 hover:text-red-600 px-4 py-2.5 rounded-xl font-bold transition-all border border-slate-200 hover:border-red-200"
                    >
                      <Ban className="w-4 h-4" /> Annuler
                    </button>
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

export default MyAppointments;
