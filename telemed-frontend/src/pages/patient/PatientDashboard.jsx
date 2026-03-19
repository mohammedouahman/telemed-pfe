import React, { useState, useEffect } from 'react';
import api from '../../services/api';
import DoctorCard from '../../components/DoctorCard';
import { Search, Filter, Loader2 } from 'lucide-react';

const PatientDashboard = () => {
  const [doctors, setDoctors] = useState([]);
  const [specialties, setSpecialties] = useState([]);
  const [loading, setLoading] = useState(true);
  
  const [filters, setFilters] = useState({
    name: '',
    specialty: ''
  });

  useEffect(() => {
    fetchSpecialties();
    fetchDoctors();
  }, []);

  const fetchSpecialties = async () => {
    try {
      const res = await api.get('/specialties');
      setSpecialties(res.data);
    } catch (e) {
      console.error(e);
    }
  };

  const fetchDoctors = async (currentFilters = filters) => {
    try {
      setLoading(true);
      const params = new URLSearchParams();
      if (currentFilters.name) params.append('name', currentFilters.name);
      if (currentFilters.specialty) params.append('specialty', currentFilters.specialty);
      
      const res = await api.get(`/doctors?${params.toString()}`);
      setDoctors(res.data.data || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleSearchClick = () => {
    fetchDoctors(filters);
  };

  const clearFilters = () => {
    const defaultFilters = { name: '', specialty: '' };
    setFilters(defaultFilters);
    fetchDoctors(defaultFilters);
  };

  return (
    <div className="min-h-[calc(100vh-64px)] bg-slate-50 py-10 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        <div className="mb-8">
          <h1 className="text-3xl font-extrabold text-slate-900">Trouvez votre Médecin</h1>
          <p className="mt-2 text-slate-600">Réservez une consultation vidéo avec nos spécialistes vérifiés.</p>
        </div>

        <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-10 flex flex-col md:flex-row gap-4 items-center">
          <div className="flex-1 w-full relative">
            <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <Search className="h-5 w-5 text-slate-400" />
            </div>
            <input
              type="text"
              placeholder="Nom du médecin (ex: Arrami)"
              className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm font-medium"
              value={filters.name}
              onChange={(e) => setFilters({...filters, name: e.target.value})}
              onKeyDown={(e) => e.key === 'Enter' && handleSearchClick()}
            />
          </div>
          
          <div className="flex-1 w-full relative">
            <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <Filter className="h-5 w-5 text-slate-400" />
            </div>
            <select
              className="w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm font-medium appearance-none"
              value={filters.specialty}
              onChange={(e) => setFilters({...filters, specialty: e.target.value})}
            >
              <option value="">Toutes les spécialités</option>
              {specialties.map(spec => (
                <option key={spec.id} value={spec.name}>{spec.name}</option>
              ))}
            </select>
          </div>

          <div className="flex w-full md:w-auto gap-3">
            <button
              onClick={handleSearchClick}
              className="flex-1 md:flex-none bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold shadow-md transition-colors"
            >
              Rechercher
            </button>
            {(filters.name || filters.specialty) && (
              <button
                onClick={clearFilters}
                className="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-3 rounded-xl font-semibold transition-colors"
              >
                Réinitialiser
              </button>
            )}
          </div>
        </div>

        {loading ? (
          <div className="flex flex-col items-center justify-center py-20">
            <Loader2 className="w-10 h-10 text-blue-500 animate-spin mb-4" />
            <p className="text-slate-500 font-medium">Recherche de spécialistes...</p>
          </div>
        ) : (
          <>
            <div className="mb-6 flex justify-between items-center">
              <h2 className="text-xl font-bold text-slate-800">{doctors.length} praticien(s) trouvé(s)</h2>
            </div>
            {doctors.length === 0 ? (
              <div className="text-center bg-white rounded-2xl p-12 lg:p-24 border border-dashed border-slate-300">
                <Search className="h-12 w-12 text-slate-300 mx-auto mb-4" />
                <h3 className="text-lg font-bold text-slate-900">Aucun médecin trouvé</h3>
                <p className="text-slate-500 mt-2">Essayez de modifier vos critères de recherche.</p>
              </div>
            ) : (
              <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                {doctors.map(doctor => (
                  <DoctorCard key={doctor.id} doctor={doctor} />
                ))}
              </div>
            )}
          </>
        )}
      </div>
    </div>
  );
};

export default PatientDashboard;
