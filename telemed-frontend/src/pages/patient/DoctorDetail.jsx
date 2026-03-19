import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import api from '../../services/api';
import toast from 'react-hot-toast';
import { MapPin, Star, GraduationCap, Calendar, Clock, CheckCircle } from 'lucide-react';
import { format, addDays } from 'date-fns';
import { fr } from 'date-fns/locale';

const DoctorDetail = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [doctor, setDoctor] = useState(null);
  const [loading, setLoading] = useState(true);
  const [bookingLoading, setBookingLoading] = useState(false);
  
  // Setup 5 upcoming mock dates starting from tomorrow for the demo
  const upcomingDates = Array.from({length: 5}).map((_, i) => addDays(new Date(), i + 1));
  const [selectedDate, setSelectedDate] = useState(upcomingDates[0]);
  const [selectedTime, setSelectedTime] = useState(null);

  useEffect(() => {
    fetchDoctor();
  }, [id]);

  const fetchDoctor = async () => {
    try {
      const res = await api.get(`/doctors/${id}`);
      setDoctor(res.data);
    } catch (err) {
      toast.error('Médecin introuvable');
      navigate('/patient');
    } finally {
      setLoading(false);
    }
  };

  const handleBooking = async () => {
    if (!selectedTime) return toast.error('Veuillez séléctionner une heure');
    const [startH, startM] = selectedTime.split(':');
    
    // Determine end time 30 mins later
    const endMins = (parseInt(startM) + 30) % 60;
    const endHours = parseInt(startH) + Math.floor((parseInt(startM) + 30) / 60);
    const endTime = `${String(endHours).padStart(2, '0')}:${String(endMins).padStart(2, '0')}`;

    try {
      setBookingLoading(true);
      await api.post('/appointments', {
        doctor_id: doctor.id,
        appointment_date: format(selectedDate, 'yyyy-MM-dd'),
        start_time: selectedTime,
        end_time: endTime
      });
      toast.success('Rendez-vous réservé avec succès !', { duration: 4000 });
      navigate('/patient/appointments');
    } catch (err) {
      toast.error(err.response?.data?.message || 'Erreur lors de la réservation');
    } finally {
      setBookingLoading(false);
    }
  };

  if (loading) return <div className="p-20 text-center font-bold text-slate-400 animate-pulse">Chargement du profil...</div>;
  if (!doctor) return null;

  const profile = doctor.doctor_profile;
  const availabilities = profile?.availabilities || [];
  
  // Very naive frontend availabilities mapping for demo purposes
  const dayNameEn = format(selectedDate, 'EEEE').toLowerCase();
  const daySlots = availabilities.filter(a => a.day_of_week === dayNameEn);
  
  // Generating slot chunks
  let generatedSlots = [];
  if (daySlots.length === 0) {
    // Fallback Mock slots if no real DB availabilities match exactly to show the UI
    generatedSlots = ['09:00', '09:30', '10:00', '10:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];
  } else {
    // Generate exactly between start_time and end_time
    daySlots.forEach(slot => {
      let current = new Date(`1970-01-01T${slot.start_time}`);
      const end = new Date(`1970-01-01T${slot.end_time}`);
      while (current < end) {
        generatedSlots.push(format(current, 'HH:mm'));
        current = new Date(current.getTime() + 30 * 60000); // add 30 mins
      }
    });
  }

  return (
    <div className="min-h-[calc(100vh-64px)] bg-slate-50 py-10 px-4 sm:px-6 lg:px-8">
      <div className="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {/* Left Col: Profile info */}
        <div className="lg:col-span-2 space-y-6">
          <div className="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
            <div className="flex flex-col sm:flex-row gap-6 items-start">
              <img src={doctor.avatar || 'https://i.pravatar.cc/150'} alt={doctor.name} className="w-32 h-32 rounded-2xl object-cover ring-4 ring-slate-50 shadow-md" />
              <div>
                <div className="flex items-center gap-3 mb-2">
                  <h1 className="text-3xl font-extrabold text-slate-900">{doctor.name}</h1>
                  {profile.is_verified && <CheckCircle className="w-6 h-6 text-blue-500" />}
                </div>
                <h2 className="text-xl font-medium text-blue-600 mb-4">{profile.specialty}</h2>
                
                <div className="flex flex-wrap gap-4 text-sm font-medium text-slate-600">
                  <div className="flex items-center gap-1.5 bg-slate-100 px-3 py-1.5 rounded-lg">
                    <MapPin className="w-4 h-4 text-slate-400" />
                    {profile.city || 'Maroc'}
                  </div>
                  <div className="flex items-center gap-1.5 bg-slate-100 px-3 py-1.5 rounded-lg">
                    <GraduationCap className="w-4 h-4 text-slate-400" />
                    {profile.experience_years} ans d'expérience
                  </div>
                  <div className="flex items-center gap-1.5 bg-amber-50 text-amber-700 px-3 py-1.5 rounded-lg">
                    <Star className="w-4 h-4 fill-amber-500" />
                    {profile.rating_average} / 5
                  </div>
                </div>
              </div>
            </div>

            <div className="mt-10">
              <h3 className="text-lg font-bold text-slate-900 mb-3 border-b pb-2">Présentation</h3>
              <p className="text-slate-600 leading-relaxed">
                {profile.bio || `Le Dr. ${doctor.name} est un spécialiste qualifié en ${profile.specialty}. Connectez-vous avec lui en vidéo pour un diagnostic précis et une prescription médicale.`}
              </p>
            </div>
          </div>
        </div>

        {/* Right Col: Booking Widget */}
        <div className="lg:col-span-1">
          <div className="bg-white rounded-3xl p-6 border border-slate-100 shadow-xl sticky top-24">
            <h3 className="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
              <Calendar className="w-6 h-6 text-blue-500" />
              Prendre Rendez-vous
            </h3>

            {/* Date Selector */}
            <div className="flex gap-2 overflow-x-auto pb-4 mb-2 -mx-2 px-2 snap-x scrollbar-hide">
              {upcomingDates.map((date, idx) => {
                const isSelected = format(date, 'yyyy-MM-dd') === format(selectedDate, 'yyyy-MM-dd');
                return (
                  <button
                    key={idx}
                    onClick={() => { setSelectedDate(date); setSelectedTime(null); }}
                    className={`flex-shrink-0 snap-start w-16 p-3 rounded-2xl flex flex-col items-center justify-center transition-all border
                      ${isSelected ? 'bg-blue-600 text-white border-blue-600 shadow-md ring-2 ring-blue-300 ring-offset-2' : 'bg-white text-slate-600 border-slate-200 hover:border-blue-300 hover:bg-blue-50'}`}
                  >
                    <span className={`text-xs font-bold uppercase ${isSelected ? 'text-blue-200' : 'text-slate-400'}`}>
                      {format(date, 'eee', {locale: fr})}
                    </span>
                    <span className="text-xl font-extrabold my-1">{format(date, 'dd')}</span>
                    <span className={`text-xs ${isSelected ? 'text-blue-200' : 'text-slate-400'}`}>
                      {format(date, 'MMM', {locale: fr})}
                    </span>
                  </button>
                )
              })}
            </div>

            <hr className="my-6 border-slate-100" />

            {/* Time Selector */}
            <div className="mb-8">
              <h4 className="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                <Clock className="w-4 h-4 text-slate-400" />
                Créneaux disponibles
              </h4>
              <div className="grid grid-cols-3 gap-2">
                {generatedSlots.length > 0 ? (
                  generatedSlots.map((time, idx) => (
                    <button
                      key={idx}
                      onClick={() => setSelectedTime(time)}
                      className={`py-2 px-1 text-sm font-bold rounded-xl border transition-all
                        ${selectedTime === time ? 'bg-emerald-500 border-emerald-500 text-white shadow-md' : 'bg-slate-50 border-slate-200 text-slate-700 hover:border-emerald-300 hover:bg-emerald-50'}`}
                    >
                      {time}
                    </button>
                  ))
                ) : (
                  <div className="col-span-3 text-center text-sm text-slate-500 py-4 bg-slate-50 rounded-xl">
                    Aucun créneau aujourd'hui
                  </div>
                )}
              </div>
            </div>

            {/* Price & CTA */}
            <div className="bg-slate-50 rounded-2xl p-4 mb-4 flex justify-between items-center">
              <span className="text-sm font-medium text-slate-500">Tarif consultation</span>
              <span className="text-xl font-bold text-slate-900">{profile.consultation_fee} €</span>
            </div>

            <button
              disabled={!selectedTime || bookingLoading}
              onClick={handleBooking}
              className="w-full py-4 rounded-xl font-bold text-white transition-all bg-blue-600 hover:bg-blue-700 hover:-translate-y-0.5 shadow-lg disabled:opacity-50 disabled:hover:translate-y-0 disabled:shadow-none"
            >
              {bookingLoading ? 'Réservation...' : 'Confirmer la réservation'}
            </button>
          </div>
        </div>

      </div>
    </div>
  );
};

export default DoctorDetail;
