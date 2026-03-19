import React from 'react';
import { Link } from 'react-router-dom';
import { Search, Video, FileText, CheckCircle, PhoneCall, Star } from 'lucide-react';

const LandingPage = () => {
  return (
    <div className="bg-slate-50 min-h-screen font-sans">
      {/* Hero Section */}
      <section className="relative overflow-hidden bg-white">
        <div className="absolute inset-0 bg-blue-50/50">
          <div className="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-l from-blue-100/50 to-transparent"></div>
        </div>
        
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative pt-20 pb-24 lg:pt-32 lg:pb-36 flex flex-col lg:flex-row items-center">
          
          <div className="lg:w-1/2 lg:pr-12 text-center lg:text-left z-10">
            <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-blue-900 tracking-tight leading-tight mb-6">
              Consultez un médecin en ligne, <span className="text-blue-600 relative inline-block">simplement.<div className="absolute -bottom-2 left-0 w-full h-3 bg-blue-200/50 -z-10 transform -rotate-2"></div></span>
            </h1>
            <p className="text-lg sm:text-xl text-slate-600 mb-8 max-w-2xl mx-auto lg:mx-0">
              Trouvez le bon spécialiste, prenez rendez-vous et effectuez votre consultation en vidéo HD depuis le confort de votre domicile. Obtenez votre ordonnance instantanément.
            </p>
            
            <div className="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-10">
              <Link to="/login" className="px-8 py-4 text-center rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ring-2 ring-transparent hover:ring-blue-300">
                Trouver un médecin
              </Link>
              <Link to="/login" className="px-8 py-4 text-center rounded-xl font-bold text-blue-700 bg-white border border-blue-100 shadow hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                Espace Médecin
              </Link>
            </div>
            
            <div className="flex items-center justify-center lg:justify-start gap-4">
              <div className="flex -space-x-4">
                <img className="w-10 h-10 rounded-full border-2 border-white object-cover" src="https://i.pravatar.cc/100?img=1" alt="Patient" />
                <img className="w-10 h-10 rounded-full border-2 border-white object-cover" src="https://i.pravatar.cc/100?img=2" alt="Patient" />
                <img className="w-10 h-10 rounded-full border-2 border-white object-cover" src="https://i.pravatar.cc/100?img=3" alt="Patient" />
                <div className="w-10 h-10 rounded-full border-2 border-white bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700">+2k</div>
              </div>
              <div className="text-sm">
                <div className="flex text-amber-500">
                  <Star className="w-4 h-4 fill-current"/><Star className="w-4 h-4 fill-current"/><Star className="w-4 h-4 fill-current"/><Star className="w-4 h-4 fill-current"/><Star className="w-4 h-4 fill-current"/>
                </div>
                <div className="font-medium text-slate-600">Patients satisfaits</div>
              </div>
            </div>
          </div>

          <div className="lg:w-1/2 mt-16 lg:mt-0 relative z-10 flex justify-center">
            <div className="relative w-full max-w-md">
              <div className="absolute inset-0 bg-blue-400 rounded-3xl transform rotate-3 scale-105 opacity-20 blur-xl"></div>
              <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?q=80&w=2070&auto=format&fit=crop" alt="Doctor Online" className="relative rounded-3xl shadow-2xl object-cover h-[500px] w-full" />
              
              <div className="absolute -left-8 top-20 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce" style={{animationDuration: '3s'}}>
                <div className="bg-red-100 p-2 rounded-full text-red-500">
                  <PhoneCall className="w-5 h-5 animate-pulse" />
                </div>
                <div>
                  <div className="text-sm font-bold text-slate-900">Appel en cours</div>
                  <div className="text-xs text-slate-500">05:23</div>
                </div>
              </div>

              <div className="absolute -right-6 bottom-20 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-3 transition-transform hover:scale-105">
                <div className="bg-emerald-100 p-2 rounded-full text-emerald-500">
                  <CheckCircle className="w-6 h-6" />
                </div>
                <div>
                  <div className="text-sm font-bold text-slate-900">Médecins</div>
                  <div className="text-xs text-slate-500">Vérifié & Certifié</div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

      {/* Features */}
      <section className="py-24 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-3xl mx-auto mb-16">
            <h2 className="text-base text-blue-600 font-semibold tracking-wide uppercase">Avantages</h2>
            <p className="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-900 sm:text-4xl">
              Pourquoi choisir TeleMed ?
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-10">
            <div className="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group">
              <div className="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <Search className="w-7 h-7" />
              </div>
              <h3 className="text-xl font-bold text-slate-900 mb-3">Recherche simple</h3>
              <p className="text-slate-600 leading-relaxed">Trouvez un médecin par spécialité ou ville en quelques clics et accédez à ses disponibilités en temps réel.</p>
            </div>

            <div className="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group">
              <div className="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <Video className="w-7 h-7" />
              </div>
              <h3 className="text-xl font-bold text-slate-900 mb-3">Vidéo HD</h3>
              <p className="text-slate-600 leading-relaxed">Communiquez de manière fluide et sécurisée grâce à notre interface d'appel vidéo intégrée, sans installation requise.</p>
            </div>

            <div className="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group">
              <div className="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                <FileText className="w-7 h-7" />
              </div>
              <h3 className="text-xl font-bold text-slate-900 mb-3">Ordonnance sécurisée</h3>
              <p className="text-slate-600 leading-relaxed">Recevez votre ordonnance au format PDF validée par la signature électronique du praticien instantanément.</p>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-white border-t border-slate-200 pt-16 pb-8">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col md:flex-row justify-between items-center">
            <div className="flex items-center space-x-2 text-blue-900 mb-4 md:mb-0">
              <div className="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">TM</div>
              <span className="font-bold text-xl tracking-tight">TeleMed</span>
            </div>
            <div className="flex space-x-6 text-sm text-slate-500">
              <a href="#" className="hover:text-blue-600">À propos</a>
              <a href="#" className="hover:text-blue-600">Confidentialité</a>
              <a href="#" className="hover:text-blue-600">Conditions</a>
              <a href="#" className="hover:text-blue-600">Contact</a>
            </div>
          </div>
          <div className="mt-8 text-center text-sm text-slate-400">
            &copy; {new Date().getFullYear()} TeleMed - Projet PFE. Tous droits réservés.
          </div>
        </div>
      </footer>
    </div>
  );
};

export default LandingPage;
