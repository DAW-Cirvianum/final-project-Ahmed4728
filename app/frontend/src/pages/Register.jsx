import React, { useState } from 'react';
import { API_URL } from '../config';
import { useNavigate } from 'react-router-dom';

function Register() {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const res = await fetch(`${API_URL}/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ nom: name, email, password })
      });

      const data = await res.json();

      if (!res.ok || !data.exit) {
        setError(data.missatge || 'Error en el registre');
      } else {
        alert('Registre completat! Ara pots iniciar sessió.');
        navigate('/login'); 
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
      <h2>Registrar-se</h2>

      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <label>Nom</label>
          <input
            type="text"
            className="form-control"
            value={name}
            onChange={e => setName(e.target.value)}
            required
          />
        </div>

        <div className="mb-3">
          <label>Email</label>
          <input
            type="email"
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
        {loading && <p className="text-info">Carregant...</p>}

        <button type="submit" className="btn btn-success" disabled={loading}>
          Registrar
        </button>
      </form>

      <hr />
      <p>
        Ja tens compte? <a href="/login">Inicia sessió</a>
      </p>
    </div>
  );
}

export default Register;
