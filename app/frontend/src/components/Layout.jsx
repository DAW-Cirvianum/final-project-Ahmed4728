import React, { useEffect, useState } from 'react';
import { Outlet, NavLink, useNavigate } from 'react-router-dom';

export default function Layout() {
  const navigate = useNavigate();
  const [token, setToken] = useState(localStorage.getItem('token'));

  useEffect(() => {
    const onStorage = () => setToken(localStorage.getItem('token'));
    window.addEventListener('storage', onStorage);
    return () => window.removeEventListener('storage', onStorage);
  }, []);

  function handleLogout() {
    localStorage.removeItem('token');
    setToken(null);
    navigate('/login');
  }

  return (
    <div>
      <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
        <div className="container">
          <NavLink className="navbar-brand" to="/products">Gestió d'inventari</NavLink>
          <div className="collapse navbar-collapse">
            <ul className="navbar-nav me-auto">
              <li className="nav-item">
                <NavLink className="nav-link" to="/products">Productes</NavLink>
              </li>
              <li className="nav-item">
                <NavLink className="nav-link" to="/orders">Comandes</NavLink>
              </li>
              <li className="nav-item">
                <NavLink className="nav-link" to="/clients">Clients</NavLink>
              </li>
              <li className="nav-item">
                <NavLink className="nav-link" to="/proveidors">Proveidors</NavLink>
              </li>
              <li className="nav-item">
                <NavLink className="nav-link" to="/categories">Categories</NavLink>
              </li>
            </ul>
            <div className="d-flex">
              {token ? (
                <button className="btn btn-outline-light" onClick={handleLogout}>Tancar sessió</button>
              ) : (
                <NavLink className="btn btn-outline-light" to="/login">Entrar</NavLink>
              )}
            </div>
          </div>
        </div>
      </nav>

      <div className="container mt-4">
        <Outlet />
      </div>
    </div>
  );
}
