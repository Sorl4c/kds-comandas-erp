// src/hooks/useCurrentTime.js
import { useState, useEffect } from 'react';

const useCurrentTime = (updateIntervalMs = 1000) => {
  const [currentTime, setCurrentTime] = useState(Date.now());

  useEffect(() => {
    const timer = setInterval(() => setCurrentTime(Date.now()), updateIntervalMs);
    
    // Cleanup: Muy importante para no dejar intervalos corriendo al desmontar
    return () => clearInterval(timer);
  }, [updateIntervalMs]);

  return currentTime;
};

export default useCurrentTime;
