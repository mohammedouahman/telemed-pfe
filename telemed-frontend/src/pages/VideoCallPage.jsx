import React, { useContext } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import { ArrowLeft } from 'lucide-react';

const VideoCallPage = () => {
  const { roomId } = useParams();
  const { user } = useContext(AuthContext);
  const navigate = useNavigate();

  // Using Jitsi Meet Iframe API natively as an iframe since react-sdk was problematic
  // https://jitsi.github.io/handbook/docs/dev-guide/dev-guide-iframe/
  const domain = "meet.jit.si";
  const jitsiUrl = `https://${domain}/${roomId}#config.prejoinPageEnabled=false&userInfo.displayName="${encodeURIComponent(user.name)}"`;

  return (
    <div className="h-[calc(100vh-64px)] flex flex-col bg-slate-900">
      <div className="bg-slate-800 text-white p-4 flex items-center justify-between border-b border-slate-700">
        <div className="flex items-center gap-4">
          <button 
            onClick={() => navigate(-1)} 
            className="p-2 hover:bg-slate-700 rounded-full transition-colors"
          >
            <ArrowLeft className="w-5 h-5" />
          </button>
          <h1 className="font-bold text-lg">Consultation Vidéo - TeleMed</h1>
        </div>
        <div className="text-sm text-slate-400 font-mono">
          ID: {roomId}
        </div>
      </div>
      
      <div className="flex-grow w-full relative bg-black">
        <iframe 
          src={jitsiUrl}
          allow="camera; microphone; fullscreen; display-capture; autoplay"
          className="absolute inset-0 w-full h-full border-0"
          title="Jitsi Meet TeleMed"
        ></iframe>
      </div>
    </div>
  );
};

export default VideoCallPage;
