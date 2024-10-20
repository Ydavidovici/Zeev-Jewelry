// src/services/authService.js
import api from './api';

export const register = (data) => api.post('/register', data);
export const login = (data) => api.post('/login', data);
export const logout = () => api.post('/logout');
export const sendResetLinkEmail = (email) =>
  api.post('/password/email', { email });
export const resetPassword = (data) => api.post('/password/reset', data);
export const changePassword = (data) => api.post('/password/change', data);
export const resendVerificationEmail = (email) =>
  api.post('/resend-verification', { email });
export const verifyEmail = (id, hash) => api.get(`/email/verify/${id}/${hash}`);
export const getCurrentUser = () => api.get('/user'); // Assuming a /user endpoint exists
