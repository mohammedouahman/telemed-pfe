import React, { useContext, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import { Bell, HeartPulse, LogOut, User } from 'lucide-react';

const Navbar = () => {
  const { user, logout } = useContext(AuthContext);
  const navigate = useNavigate();
  const [showNotifications, setShowNotifications] = useState(false);

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <nav className="bg-white border-b border-slate-200 sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16 items-center">
          <div className="flex items-center space-x-2 text-primary">
            <HeartPulse className="h-8 w-8 text-blue-600" />
            <Link to={user ? `/${user.role}` : '/'} className="font-bold text-xl tracking-tight text-blue-900">
              TeleMed
            </Link>
          </div>

          <div className="flex items-center space-x-6">
            {!user ? (
              <>
                <Link to="/" className="text-slate-600 hover:text-blue-600 font-medium text-sm">Accueil</Link>
                <Link to="/login" className="text-blue-600 font-medium text-sm hover:underline">Connexion</Link>
                <Link to="/register" className="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-blue-700 transition">S'inscrire</Link>
              </>
            ) : (
              <>
                {user.role === 'patient' && (
                  <Link to="/patient/appointments" className="text-slate-600 hover:text-blue-600 font-medium text-sm">Mes Rendez-vous</Link>
                )}
                {user.role === 'doctor' && (
                  <>
                    <Link to="/doctor" className="text-slate-600 hover:text-blue-600 font-medium text-sm">Mon Agenda</Link>
                    <Link to="/doctor/patients" className="text-slate-600 hover:text-blue-600 font-medium text-sm">Mes Patients</Link>
                  </>
                )}
                {user.role === 'admin' && (
                  <>
                    <Link to="/admin" className="text-slate-600 hover:text-blue-600 font-medium text-sm">Dashboard</Link>
                    <Link to="/admin/doctors" className="text-slate-600 hover:text-blue-600 font-medium text-sm">Médecins</Link>
                    <Link to="/admin/specialties" className="text-slate-600 hover:text-blue-600 font-medium text-sm">Spécialités</Link>
                  </>
                )}
                
                <div className="relative">
                  <button onClick={() => setShowNotifications(!showNotifications)} className="p-2 text-slate-500 hover:text-blue-600 transition focus:outline-none">
                    <Bell className="w-5 h-5" />
                    <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                  </button>

                  {showNotifications && (
                    <div className="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 animate-fade-in-up">
                      <div className="px-4 py-2 border-b border-slate-100">
                        <h3 className="text-sm font-bold text-slate-800">Notifications</h3>
                      </div>
                      <div className="p-6 text-center text-sm text-slate-500 font-medium">
                        Aucune nouvelle notification pour le moment.
                      </div>
                    </div>
                  )}
                </div>

                <div className="flex items-center space-x-3 border-l pl-6 border-slate-200">
                  <div className="flex flex-col text-right hidden sm:block">
                    <span className="text-sm font-semibold text-slate-900 leading-none">{user.name}</span>
                    <span className="text-xs text-slate-500 capitalize">{user.role}</span>
                  </div>
                  {user.avatar ? (
                    <img src={user.avatar} alt="avatar" className="w-9 h-9 rounded-full object-cover border border-slate-200" />
                  ) : (
                    <div className="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                      <User className="w-5 h-5" />
                    </div>
                  )}
                  <button onClick={handleLogout} className="p-2 text-slate-400 hover:text-red-500 transition" title="Se déconnecter">
                    <LogOut className="w-5 h-5" />
                  </button>
                </div>
              </>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
