// src/router/Routes.js
import React, { Suspense, lazy } from 'react';
import { Routes, Route } from 'react-router-dom';
import ProtectedRoute from '../components/Auth/ProtectedRoute';
import Loader from '../components/Common/Loader';

// Lazy-loaded components for performance optimization
const Home = lazy(() => import('../pages/Home'));
const Login = lazy(() => import('../pages/Login'));
const Register = lazy(() => import('../pages/Register'));
const ForgotPassword = lazy(() => import('../pages/ForgotPassword'));
const ResetPassword = lazy(() => import('../pages/ResetPassword'));
const ChangePassword = lazy(() => import('../pages/ChangePassword'));
const ResendVerification = lazy(() => import('../pages/ResendVerification'));

const AdminDashboardPage = lazy(
  () => import('../pages/Admin/AdminDashboardPage')
);
const UsersPage = lazy(() => import('../pages/Admin/UsersPage'));
const RolesPage = lazy(() => import('../pages/Admin/RolesPage'));

const ClientDashboardPage = lazy(
  () => import('../pages/Client/ClientDashboardPage')
);
const DeveloperDashboardPage = lazy(
  () => import('../pages/Developer/DeveloperDashboardPage')
);

const ProductsPage = lazy(() => import('../pages/Products/ProductsPage.js'));
const CartPage = lazy(() => import('../pages/Cart/CartPage.js'));
const CheckoutPage = lazy(() => import('../pages/Checkout/CheckoutPage.js'));
const OrdersPage = lazy(() => import('../pages/Orders/OrdersPage.js'));
const ReviewsPage = lazy(() => import('../pages/Reviews/ReviewsPage.js'));

const About = lazy(() => import('../pages/About'));

const AppRoutes = () => (
  <Suspense fallback={<Loader />}>
    <Routes>
      {/* Public Routes */}
      <Route path="/" element={<Home />} />
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />
      <Route path="/forgot-password" element={<ForgotPassword />} />
      <Route path="/reset-password" element={<ResetPassword />} />
      <Route path="/resend-verification" element={<ResendVerification />} />

      {/* Protected Routes */}
      <Route
        path="/change-password"
        element={
          <ProtectedRoute>
            <ChangePassword />
          </ProtectedRoute>
        }
      />

      {/* Admin Routes */}
      <Route
        path="/admin/dashboard"
        element={
          <ProtectedRoute roles={['admin']}>
            <AdminDashboardPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/users"
        element={
          <ProtectedRoute roles={['admin']}>
            <UsersPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/roles"
        element={
          <ProtectedRoute roles={['admin']}>
            <RolesPage />
          </ProtectedRoute>
        }
      />

      {/* Client Routes */}
      <Route
        path="/client/dashboard"
        element={
          <ProtectedRoute roles={['client']}>
            <ClientDashboardPage />
          </ProtectedRoute>
        }
      />

      {/* Developer Routes */}
      <Route
        path="/developer/dashboard"
        element={
          <ProtectedRoute roles={['developer']}>
            <DeveloperDashboardPage />
          </ProtectedRoute>
        }
      />

      {/* Resource Routes */}
      <Route
        path="/products"
        element={
          <ProtectedRoute>
            <ProductsPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/cart"
        element={
          <ProtectedRoute>
            <CartPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/checkout"
        element={
          <ProtectedRoute>
            <CheckoutPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/orders"
        element={
          <ProtectedRoute>
            <OrdersPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/reviews"
        element={
          <ProtectedRoute>
            <ReviewsPage />
          </ProtectedRoute>
        }
      />

      {/* Other Routes */}
      <Route path="/about" element={<About />} />

      {/* 404 Not Found */}
      <Route path="*" element={<div>404 Not Found</div>} />
    </Routes>
  </Suspense>
);

export default AppRoutes;
