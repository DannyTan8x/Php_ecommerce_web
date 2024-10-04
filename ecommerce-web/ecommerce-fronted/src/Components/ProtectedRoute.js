import React from "react";
import { Navigate, Outlet } from "react-router-dom";
import { jwtDecode } from "jwt-decode";

const ProtectedRoute = ({ roleRequired }) => {
  const isAuthenticated = !!localStorage.getItem("token");
  const userRole = localStorage.getItem("role");

  if (!isAuthenticated) {
    return <Navigate to="/login" />;
  } else {
    try {
      const token = localStorage.getItem("token");
      const decodedToken = jwtDecode(token);
      const currentTime = Date.now() / 1000;
      if (decodedToken.exp < currentTime) {
        //Token is expired
        localStorage.removeItem("token");
        localStorage.removeItem("role");
        return <Navigate to="/login" />;
      }
    } catch (error) {
      //Token is invalid
      localStorage.removeItem("token");
      localStorage.removeItem("role");
      return <Navigate to="/login" />;
    }
  }

  if (isAuthenticated && userRole !== roleRequired) {
    return <Navigate to="/unauthorized" />;
  }

  return <Outlet />;
};

export default ProtectedRoute;
