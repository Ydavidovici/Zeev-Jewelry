// src/App.js
import React from 'react';
import Navbar from './components/Common/Navbar';
import Routes from './router/Routes';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const App = () => {
  return (
    <div className="App">
      <Navbar />
      <Routes />
      <ToastContainer />
    </div>
  );
};

export default App;
