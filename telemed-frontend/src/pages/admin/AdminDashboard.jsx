import React, { useState, useEffect } from 'react';
import api from '../../services/api';
import toast from 'react-hot-toast';
import { ShieldCheck, UserCheck, AlertTriangle, Loader2, RefreshCcw } from 'lucide-react';

const AdminDashboard = () => {
  const [stats, setStats] = useState(null);
  const [pendingDoctors, setPendingDoctors] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      setLoading(true);
      const [statsRes, doctorsRes] = await Promise.all([
        api.get('/admin/stats'),
        api.get('/admin/doctors/pending')
      ]);
      setStats(statsRes.data);
      setPendingDoctors(doctorsRes.data);
    } catch (e) {
      toast.error('Erreur de chargement des données API');
    } finally {
      setLoading(false);
    }
  };

  const handleVerify = async (id) => {
    try {
      toast.loading('Vérification...', {id: 'verify'});
      await api.put(`/admin/doctors/${id}/verify`);
      toast.success('Médecin vérifié avec succès', {id: 'verify'});
      fetchData(); // reload
    } catch (e) {
      toast.error('Erreur lors de la vérification', {id: 'verify'});
    }
  };

  if (loading && !stats) return <div className="flex justify-center p-20"><Loader2 className="w-8 h-8 animate-spin text-blue-500" /></div>;

  return (
    <div className="min-h-[calc(100vh-64px)] bg-slate-900 py-10 px-4 sm:px-6 lg:px-8 text-white">
      <div className="max-w-7xl mx-auto">
        <div className="flex justify-between items-center mb-10">
          <div>
            <h1 className="text-3xl font-extrabold flex items-center gap-3">
              <ShieldCheck className="w-8 h-8 text-emerald-400" />
              Panneau d'Administration
            </h1>
            <p className="text-slate-400 mt-2">Gestion de la plateforme et vérifications de sécurité.</p>
          </div>
          <button onClick={fetchData} className="flex items-center gap-2 bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg font-medium text-slate-300 transition-colors">
            <RefreshCcw className="w-4 h-4" /> Rafraîchir
          </button>
        </div>

        {/* Dash Stats */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
           <div className="bg-slate-800 p-6 rounded-2xl border border-slate-700">
            <p className="text-sm font-medium text-slate-400">Total Patients</p>
            <p className="text-3xl font-bold mt-2">{stats?.total_patients || 0}</p>
          </div>
          <div className="bg-slate-800 p-6 rounded-2xl border border-slate-700">
            <p className="text-sm font-medium text-slate-400">Total Médecins</p>
            <p className="text-3xl font-bold mt-2">{stats?.total_doctors || 0}</p>
          </div>
          <div className="bg-slate-800 p-6 rounded-2xl border border-slate-700">
            <p className="text-sm font-medium text-slate-400">Total Consultations</p>
            <p className="text-3xl font-bold mt-2">{stats?.total_consultations || 0}</p>
          </div>
          <div className="bg-amber-900/30 p-6 rounded-2xl border border-amber-700/50 relative overflow-hidden">
            <div className="absolute top-0 right-0 p-4 opacity-10"><AlertTriangle className="w-16 h-16 text-amber-500" /></div>
            <p className="text-sm font-medium text-amber-400 relative z-10">Médecins en Attente</p>
            <p className="text-3xl font-bold mt-2 text-amber-500 relative z-10">{stats?.pending_doctors || 0}</p>
          </div>
        </div>

        {/* Action zone */}
        <h2 className="text-xl font-bold mb-6 flex items-center gap-2">
          <UserCheck className="w-6 h-6 text-blue-400" />
          Vérification des Praticiens (KYC)
        </h2>

        {pendingDoctors.length === 0 ? (
          <div className="bg-slate-800/50 border border-emerald-900/50 rounded-2xl p-10 text-center">
            <ShieldCheck className="w-12 h-12 text-emerald-500 mx-auto mb-4" />
            <h3 className="text-xl font-bold text-slate-200">Tout est en ordre</h3>
            <p className="text-slate-400 mt-2">Il n'y a aucun profil médecin en attente de validation.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {pendingDoctors.map(doc => (
              <div key={doc.id} className="bg-slate-800 rounded-2xl p-6 border border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div className="flex items-center gap-4">
                  <img src={doc.avatar || 'https://i.pravatar.cc/150'} alt={doc.name} className="w-14 h-14 rounded-full object-cover ring-2 ring-slate-700" />
                  <div>
                    <h3 className="font-bold text-lg">{doc.name}</h3>
                    <p className="text-sm text-amber-400 font-medium">{doc.doctor_profile?.specialty || 'Spécialiste'}</p>
                    <p className="text-xs text-slate-400 mt-1">{doc.email} • INAMI/RPPS Vérifié en base</p>
                  </div>
                </div>
                
                <button 
                  onClick={() => handleVerify(doc.id)}
                  className="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-lg"
                >
                  Approuver le profil
                </button>
              </div>
            ))}
          </div>
        )}

      </div>
    </div>
  );
};

export default AdminDashboard;
