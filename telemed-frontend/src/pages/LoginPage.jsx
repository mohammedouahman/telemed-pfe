import React, { useState, useContext } from 'react';
import { AuthContext } from '../context/AuthContext';
import { useNavigate, Link } from 'react-router-dom';
import toast from 'react-hot-toast';
import { HeartPulse, ShieldCheck, Star } from 'lucide-react';

const LoginPage = () => {
  const { login } = useContext(AuthContext);
  const navigate = useNavigate();
  const [roleMode, setRoleMode] = useState('patient');
  
  const [formData, setFormData] = useState({
    email: '',
    password: ''
  });
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const { user } = await login(formData);
      toast.success(`Bienvenue, ${user.name}`);
      if (user.role === 'patient') navigate('/patient');
      if (user.role === 'doctor') navigate('/doctor');
      if (user.role === 'admin') navigate('/admin');
    } catch (err) {
      toast.error(err.response?.data?.message || 'Erreur de connexion');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-[calc(100vh-64px)] flex">
      {/* Left Panel */}
      <div className="hidden lg:flex lg:w-1/2 bg-blue-50 relative items-center justify-center overflow-hidden">
        <div className="absolute inset-0 bg-blue-600/5 mix-blend-multiply"></div>
        <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div className="absolute top-1/3 right-1/4 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        
        <div className="relative z-10 w-full max-w-lg p-12">
          <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?q=80&w=1964&auto=format&fit=crop" className="rounded-3xl shadow-2xl mb-8 object-cover h-80 w-full" alt="Doctor" />
          
          <div className="bg-white p-6 rounded-2xl shadow-xl flex items-center gap-4 relative -right-12">
            <div className="bg-blue-100 p-3 rounded-xl text-blue-600">
              <ShieldCheck className="w-8 h-8" />
            </div>
            <div>
              <h3 className="font-bold text-slate-900">Données sécurisées</h3>
              <p className="text-sm text-slate-500">Protection médicale HDS certifiée</p>
            </div>
          </div>
        </div>
      </div>

      {/* Right Panel */}
      <div className="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-12 bg-white">
        <div className="w-full max-w-md space-y-8">
          <div className="text-center">
            <HeartPulse className="mx-auto h-12 w-12 text-blue-600" />
            <h2 className="mt-6 text-3xl font-extrabold text-slate-900">Bienvenue</h2>
            <p className="mt-2 text-sm text-slate-500">
              Connectez-vous à votre espace TeleMed
            </p>
          </div>

          <div className="flex bg-slate-100 p-1 rounded-xl">
            <button
              onClick={() => setRoleMode('patient')}
              className={`flex-1 py-2 text-sm font-semibold rounded-lg transition-all ${roleMode === 'patient' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}
            >
              Patient
            </button>
            <button
              onClick={() => setRoleMode('doctor')}
              className={`flex-1 py-2 text-sm font-semibold rounded-lg transition-all ${roleMode === 'doctor' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}
            >
              Médecin
            </button>
          </div>

          <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-1">Adresse email</label>
                <input
                  type="email"
                  required
                  className="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors"
                  placeholder={roleMode === 'patient' ? 'jean@telemed.ma' : 'arrami@telemed.ma'}
                  value={formData.email}
                  onChange={(e) => setFormData({...formData, email: e.target.value})}
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-slate-700 mb-1">Mot de passe</label>
                <input
                  type="password"
                  required
                  className="appearance-none relative block w-full px-4 py-3 border border-slate-300 placeholder-slate-400 text-slate-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition-colors"
                  placeholder="••••••••"
                  value={formData.password}
                  onChange={(e) => setFormData({...formData, password: e.target.value})}
                />
              </div>
            </div>

            <div>
              <button
                type="submit"
                disabled={loading}
                className="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-70 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5"
              >
                {loading ? 'Connexion en cours...' : 'Se connecter'}
              </button>
            </div>
            
            <div className="text-center mt-4">
              <span className="text-sm text-slate-500">Pas encore de compte ? </span>
              <Link to="/register" className="font-semibold text-blue-600 hover:text-blue-500 transition-colors">
                Créer un compte
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
