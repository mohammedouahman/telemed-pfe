import React, { useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import api from '../../services/api';
import toast from 'react-hot-toast';
import { FileText, Plus, Trash2, CheckCircle } from 'lucide-react';

const WriteConsultation = () => {
  const { appointmentId } = useParams();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  
  const [consultation, setConsultation] = useState({
    diagnosis: '',
    doctor_notes: '',
    recommendations: ''
  });

  const [medications, setMedications] = useState([
    { name: '', dosage: '', frequency: '', duration: '' }
  ]);

  const addMedication = () => {
    setMedications([...medications, { name: '', dosage: '', frequency: '', duration: '' }]);
  };

  const updateMedication = (index, field, value) => {
    const newMeds = [...medications];
    newMeds[index][field] = value;
    setMedications(newMeds);
  };

  const removeMedication = (index) => {
    const newMeds = medications.filter((_, i) => i !== index);
    setMedications(newMeds);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    // Filter out empty mediations
    const validMedications = medications.filter(m => m.name.trim() !== '');

    const payload = {
      ...consultation,
      medications: validMedications
    };

    try {
      await api.post(`/consultations/${appointmentId}/prescription`, payload);
      toast.success('Consultation enregistrée avec succès');
      navigate('/doctor');
    } catch (err) {
      toast.error(err.response?.data?.message || 'Erreur lors de l\'enregistrement');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-[calc(100vh-64px)] bg-slate-50 py-10 px-4 sm:px-6 lg:px-8">
      <div className="max-w-4xl mx-auto">
        <div className="flex items-center gap-3 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
          <div className="bg-blue-100 p-3 rounded-xl text-blue-600">
            <FileText className="w-8 h-8" />
          </div>
          <div>
            <h1 className="text-2xl font-extrabold text-slate-900">Rapport de Consultation</h1>
            <p className="text-slate-500 text-sm font-medium">Rédaction de l'ordonnance et notes (RDV #{appointmentId})</p>
          </div>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Notes Internes */}
          <div className="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <h2 className="text-lg font-bold text-slate-900 mb-6 border-b pb-4">Notes Médicales</h2>
            
            <div className="space-y-5">
              <div>
                <label className="block text-sm font-bold text-slate-700 mb-2">Diagnostic <span className="text-red-500">*</span></label>
                <input
                  type="text"
                  required
                  className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium"
                  placeholder="Ex: Angine bactérienne"
                  value={consultation.diagnosis}
                  onChange={e => setConsultation({...consultation, diagnosis: e.target.value})}
                />
              </div>
              
              <div>
                <label className="block text-sm font-bold text-slate-700 mb-2">Notes pour le dossier</label>
                <textarea
                  className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium h-32 resize-none"
                  placeholder="Antécédents du patient, symptômes détaillés..."
                  value={consultation.doctor_notes}
                  onChange={e => setConsultation({...consultation, doctor_notes: e.target.value})}
                ></textarea>
              </div>
            </div>
          </div>

          {/* Ordonnance */}
          <div className="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div className="flex justify-between items-center mb-6 border-b pb-4">
              <h2 className="text-lg font-bold text-slate-900">Médicaments (Ordonnance)</h2>
              <button 
                type="button" 
                onClick={addMedication}
                className="flex items-center gap-1.5 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-sm font-bold hover:bg-blue-100 transition-colors"
              >
                <Plus className="w-4 h-4" /> Ajouter
              </button>
            </div>

            <div className="space-y-4">
              {medications.map((med, index) => (
                <div key={index} className="flex gap-3 items-end bg-slate-50 p-4 rounded-2xl border border-slate-100 relative group transition-all hover:border-slate-300">
                  <div className="flex-1">
                    <label className="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Médicament</label>
                    <input type="text" placeholder="Doliprane 1000mg" required
                      className="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none"
                      value={med.name} onChange={e => updateMedication(index, 'name', e.target.value)} />
                  </div>
                  <div className="w-24">
                    <label className="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Dosage</label>
                    <input type="text" placeholder="1 comp." required
                      className="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none"
                      value={med.dosage} onChange={e => updateMedication(index, 'dosage', e.target.value)} />
                  </div>
                  <div className="flex-1">
                    <label className="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Fréquence</label>
                    <input type="text" placeholder="Matin et Soir" required
                      className="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none"
                      value={med.frequency} onChange={e => updateMedication(index, 'frequency', e.target.value)} />
                  </div>
                  <div className="w-24">
                    <label className="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Durée</label>
                    <input type="text" placeholder="5 jours" required
                      className="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none"
                      value={med.duration} onChange={e => updateMedication(index, 'duration', e.target.value)} />
                  </div>
                  {medications.length > 1 && (
                    <button type="button" onClick={() => removeMedication(index)}
                      className="p-2 text-red-400 hover:text-white hover:bg-red-500 rounded-lg transition-colors absolute -right-2 top-1/2 -translate-y-1/2 shadow-sm border border-slate-200 bg-white opacity-0 group-hover:opacity-100"
                    >
                      <Trash2 className="w-4 h-4" />
                    </button>
                  )}
                </div>
              ))}
            </div>

            <div className="mt-8 pt-6 border-t border-slate-100">
               <label className="block text-sm font-bold text-slate-700 mb-2">Recommandations patient</label>
                <textarea
                  className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium h-24 resize-none"
                  placeholder="A imprimer sur l'ordonnance (ex: Boire beaucoup d'eau...)"
                  value={consultation.recommendations}
                  onChange={e => setConsultation({...consultation, recommendations: e.target.value})}
                ></textarea>
            </div>
          </div>

          <div className="flex justify-end pt-4 pb-20">
            <button
              type="submit"
              disabled={loading}
              className="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-bold transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 disabled:opacity-70 disabled:hover:translate-y-0"
            >
              {loading ? <Loader2 className="w-5 h-5 animate-spin" /> : <CheckCircle className="w-5 h-5" />}
              Terminer et Envoyer l'Ordonnance
            </button>
          </div>
            
        </form>
      </div>
    </div>
  );
};

export default WriteConsultation;
