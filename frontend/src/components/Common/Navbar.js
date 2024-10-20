// src/components/Common/Navbar.js
import React, { useContext } from 'react';
import { Link } from 'react-router-dom';
import { AuthContext } from '../../contexts/AuthContext';

const Navbar = () => {
  const { isAuthenticated, user, logout } = useContext(AuthContext);

  const handleLogout = async () => {
    try {
      await logout();
    } catch (error) {
      console.error('Logout failed:', error);
    }
  };

  return (
    <nav className="bg-gray-800 p-4 text-white">
      <div className="container mx-auto flex justify-between">
        <Link to="/" className="font-bold">
          Project Management
        </Link>
        <div>
          {isAuthenticated ? (
            <>
              <span className="mr-4">Hello, {user.name}</span>
              {user.role === 'admin' && (
                <Link to="/admin/dashboard" className="mr-4">
                  Admin Dashboard
                </Link>
              )}
              {user.role === 'client' && (
                <Link to="/client/dashboard" className="mr-4">
                  Client Dashboard
                </Link>
              )}
              {user.role === 'developer' && (
                <Link to="/developer/dashboard" className="mr-4">
                  Developer Dashboard
                </Link>
              )}
              <button
                onClick={handleLogout}
                className="bg-red-500 px-3 py-1 rounded"
              >
                Logout
              </button>
            </>
          ) : (
            <>
              <Link to="/login" className="mr-4">
                Login
              </Link>
              <Link to="/register" className="bg-blue-500 px-3 py-1 rounded">
                Register
              </Link>
            </>
          )}
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
