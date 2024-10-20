// src/components/Auth/LoginForm.js
import React, { useContext } from 'react';
import { useForm } from 'react-hook-form';
import { AuthContext } from '../../contexts/AuthContext';
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from 'yup';
import { toast } from 'react-toastify';

const schema = yup.object().shape({
  email: yup.string().email('Invalid email').required('Email is required'),
  password: yup.string().required('Password is required'),
});

const LoginForm = () => {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm({
    resolver: yupResolver(schema),
  });
  const { login } = useContext(AuthContext);

  const onSubmit = async (data) => {
    try {
      await login(data);
      // Redirect or perform additional actions if necessary
    } catch (error) {
      // Error handling is managed in AuthContext
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="max-w-md mx-auto mt-10">
      <h2 className="text-2xl mb-4">Login</h2>
      <div className="mb-4">
        <label className="block">Email:</label>
        <input
          type="email"
          {...register('email')}
          className="w-full p-2 border border-gray-300 rounded"
        />
        <p className="text-red-500 text-sm">{errors.email?.message}</p>
      </div>
      <div className="mb-4">
        <label className="block">Password:</label>
        <input
          type="password"
          {...register('password')}
          className="w-full p-2 border border-gray-300 rounded"
        />
        <p className="text-red-500 text-sm">{errors.password?.message}</p>
      </div>
      <button
        type="submit"
        className="w-full bg-blue-500 text-white p-2 rounded"
      >
        Login
      </button>
    </form>
  );
};

export default LoginForm;
