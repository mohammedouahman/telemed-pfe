import React from 'react';

const StatusBadge = ({ status }) => {
  const statusConfig = {
    pending: { label: 'En attente', color: 'bg-amber-100 text-amber-700 border-amber-200', dot: 'bg-amber-500' },
    confirmed: { label: 'Confirmé', color: 'bg-emerald-100 text-emerald-700 border-emerald-200', dot: 'bg-emerald-500' },
    cancelled: { label: 'Annulé', color: 'bg-red-100 text-red-700 border-red-200', dot: 'bg-red-500' },
    completed: { label: 'Terminé', color: 'bg-blue-100 text-blue-700 border-blue-200', dot: 'bg-blue-500' }
  };

  const config = statusConfig[status] || statusConfig.pending;

  return (
    <span className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border ${config.color}`}>
      <span className={`w-1.5 h-1.5 rounded-full ${config.dot}`}></span>
      {config.label}
    </span>
  );
};

export default StatusBadge;
