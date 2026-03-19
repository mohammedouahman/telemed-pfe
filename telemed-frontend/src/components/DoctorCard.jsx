import React from 'react';
import { Link } from 'react-router-dom';
import { Star, MapPin, CheckCircle } from 'lucide-react';

const DoctorCard = ({ doctor }) => {
  const profile = doctor.doctor_profile;
  return (
    <div className="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 group relative overflow-hidden">
      <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
      
      <div className="flex items-start justify-between mb-4">
        <div className="relative">
          <img src={doctor.avatar || 'https://i.pravatar.cc/150'} alt={doctor.name} className="w-16 h-16 rounded-full object-cover ring-2 ring-slate-100 group-hover:ring-blue-100 transition-all" />
          <span className="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></span>
        </div>
        <div className="flex items-center gap-1 bg-amber-50 text-amber-600 px-2 py-1 rounded-lg text-xs font-bold">
          <Star className="w-3.5 h-3.5 fill-amber-500" />
          <span>{profile?.rating_average || 'N/A'}</span>
        </div>
      </div>

      <div className="mb-4">
        <h3 className="font-bold text-slate-900 text-lg flex items-center gap-1.5">
          {doctor.name}
          {profile?.is_verified && <CheckCircle className="w-4 h-4 text-blue-500" />}
        </h3>
        <p className="text-blue-600 font-medium text-sm mb-1">{profile?.specialty || 'Spécialiste'}</p>
        <div className="flex items-center gap-1.5 text-slate-500 text-sm">
          <MapPin className="w-3.5 h-3.5" />
          <span>{profile?.city || 'Non spécifié'}</span>
        </div>
      </div>
      
      <div className="flex items-center justify-between mt-6 pt-4 border-t border-slate-100">
        <div className="text-slate-600">
          <span className="text-xl font-bold text-slate-900">{profile?.consultation_fee || '0'}€</span>
          <span className="text-xs"> /cns</span>
        </div>
        <Link 
          to={`/patient/doctors/${doctor.id}`} 
          className="bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white px-4 py-2 rounded-xl font-semibold text-sm transition-colors duration-300"
        >
          Prendre RDV
        </Link>
      </div>
    </div>
  );
};

export default DoctorCard;
