import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import { Toaster } from 'react-hot-toast';

import Navbar from './components/Navbar';
import ProtectedRoute from './components/ProtectedRoute';

// Public Pages
import LandingPage from './pages/LandingPage';
import LoginPage from './pages/LoginPage';
import RegisterPage from './pages/RegisterPage';

// Shared Protected
import VideoCallPage from './pages/VideoCallPage';

// Patient Pages
import PatientDashboard from './pages/patient/PatientDashboard';
import DoctorDetail from './pages/patient/DoctorDetail';
import MyAppointments from './pages/patient/MyAppointments';

// Doctor Pages
import DoctorDashboard from './pages/doctor/DoctorDashboard';
import WriteConsultation from './pages/doctor/WriteConsultation';

// Admin Pages
import AdminDashboard from './pages/admin/AdminDashboard';

function App() {
  return (
    <AuthProvider>
      <Router>
        <div className="min-h-screen flex flex-col font-sans">
          <Toaster position="top-right" />
          <Navbar />
          
          <main className="flex-grow">
            <Routes>
              {/* Public */}
              <Route path="/" element={<LandingPage />} />
              <Route path="/login" element={<LoginPage />} />
              <Route path="/register" element={<RegisterPage />} />

              {/* Patient */}
              <Route element={<ProtectedRoute role="patient" />}>
                <Route path="/patient" element={<PatientDashboard />} />
                <Route path="/patient/doctors/:id" element={<DoctorDetail />} />
                <Route path="/patient/appointments" element={<MyAppointments />} />
              </Route>

              {/* Doctor */}
              <Route element={<ProtectedRoute role="doctor" />}>
                <Route path="/doctor" element={<DoctorDashboard />} />
                <Route path="/doctor/consultation/:appointmentId" element={<WriteConsultation />} />
              </Route>

              {/* Admin */}
              <Route element={<ProtectedRoute role="admin" />}>
                <Route path="/admin" element={<AdminDashboard />} />
              </Route>

              {/* Shared Video Call (Patient or Doctor) */}
              <Route element={<ProtectedRoute />}>
                <Route path="/video-call/:roomId" element={<VideoCallPage />} />
              </Route>
            </Routes>
          </main>
        </div>
      </Router>
    </AuthProvider>
  );
}

export default App;
