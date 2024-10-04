import React, { useEffect } from "react";
import {
  BrowserRouter as Router,
  Route,
  Routes,
  useNavigate,
} from "react-router-dom";
// import Home from './Components/Home';
import Login from "./Components/Login";
import Admin from "./Components/Admin";
import UserDetail from "./Components/UserDetail";
import ProductList from "./Components/ProductList";
import ProtectedRoute from "./Components/ProtectedRoute";
import ProductDetail from "./Components/ProductDetail";
import UserList from "./Components/UserList";
import { setupAxiosInterceptors } from "./axiosConfig";

function App() {
  const navigate = useNavigate();

  useEffect(() => {
    setupAxiosInterceptors(navigate);
  }, [navigate]);

  return (
    // <Router>
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route path="/:id" element={<ProductDetail />} />
      <Route path="/unauthorized" element={<h2>Unauthorized</h2>} />
      <Route element={<ProtectedRoute roleRequired="admin" />}>
        <Route path="/admin" element={<Admin />} />
      </Route>
      <Route element={<ProtectedRoute roleRequired="user" />}>
        <Route path="/user" element={<UserDetail />} />
      </Route>
      <Route path="/" element={<ProductList />} />
    </Routes>
    // </Router>
  );
}

export default App;
