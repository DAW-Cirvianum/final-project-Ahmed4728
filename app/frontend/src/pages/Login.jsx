import React, { useState, useEffect } from 'react';
import { API_URL } from '../config';
import { useNavigate } from 'react-router-dom';
import { Link } from 'react-router-dom';


function Login({ setToken }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false); 
  const navigate = useNavigate();

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (token) navigate('/products');
  }, [navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const res = await fetch(`${API_URL}/login`, {
        method: 'POST',
        headers: { 
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ login: email, password }) 
      });

      const data = await res.json();

      if (!res.ok || !data.exit) {
        setError(data.missatge || 'Credencials incorrectes');
      } else {
        localStorage.setItem('token', data.token);
        if (setToken) setToken(data.token);
        navigate('/products');
      }
    } catch (err) {
      console.error(err);
      setError('Error de connexió');
    } finally {
      setLoading(false); 
    }
  };

  return (
    <div className="container mt-5">
      <h2>Iniciar sessió</h2>

      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <label>Email o nom d'usuari</label>
          <input
            type="text"
            className="form-control"
            value={email}
            onChange={e => setEmail(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label>Contrasenya</label>
          <input
            type="password"
            className="form-control"
            value={password}
            onChange={e => setPassword(e.target.value)}
            required
          />
        </div>

        {error && <p className="text-danger">{error}</p>}
        {loading && <p className="text-info">Carregant...</p>} {}

        <button type="submit" className="btn btn-primary" disabled={loading}>
          Entrar
        </button>
      </form>
      <p className="mt-3">
  No tens compte?{' '}
  <Link to="/register">Registrar-se</Link>
</p>

        <p className="mt-2">
          <a href="http://localhost/" className="btn btn-secondary">Accedir al portal de administradors</a>
        </p>

    </div>
  );
}

export default Login;
