import React, { useState } from "react";
import axiosInstance, { setAuthToken } from "../axiosConfig";
import { useNavigate } from "react-router-dom";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const response = await axiosInstance.post(
        "/utilities/login.php",
        {
          email,
          password,
        },
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
      const { jwt, role } = response.data;
      localStorage.setItem("token", jwt);
      localStorage.setItem("role", role);
      setAuthToken(jwt);
      console.log(response);
      //   setMessage("Login successful");
      navigate("/");
    } catch (error) {
      console.error("Login error:", error);
      setMessage("Login failed.");
    }
  };

  return (
    <div>
      <h2>Login</h2>
      <form onSubmit={handleLogin}>
        <input
          type="email"
          value={email}
          placeholder="Email"
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <input
          type="password"
          value={password}
          placeholder="Password"
          onChange={(e) => setPassword(e.target.value)}
          required
        />
        <button type="submit">Login</button>
      </form>
      {message && <p>{message}</p>}
    </div>
  );
}
