// src/axiosConfig
import axios from "axios";
import { useNavigate } from "react-router-dom";

// const token = localStorage.getItem('token');

const axiosInstance = axios.create({
  baseURL: "http://192.168.1.112:8000/api",
  // cmd run C:\xampp\htdocs\ecommerce-web\ecommerce-api> php -S 192.168.1.112:8000
  headers: {
    // Authorization: `Bearer ${token}`,
    "Content-Type": "application/json",
  },
});

axiosInstance.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers["Authorization"] = "Bearer " + token;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

//function to setup interceptors
export const setupAxiosInterceptors = (navigate) => {
  axiosInstance.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response && error.response.status === 401) {
        navigate("/login");
      }
      return Promise.reject(error);
    }
  );
};

//function to set authorization token.
export const setAuthToken = (token) => {
  if (token) {
    axiosInstance.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  } else {
    delete axiosInstance.defaults.headers.common["Authorization"];
  }
};

export default axiosInstance;
