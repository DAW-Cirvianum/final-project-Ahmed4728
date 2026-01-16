import { Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import Register from './pages/Register';

import ProductsList from './pages/Products/ProductsList';
import ComandesList from './pages/Comandes/ComandesList';
import ClientsList from './pages/Clients/ClientsList';
import ProveidorsList from './pages/Proveidors/ProveidorsList';
import CategoriesList from './pages/Categories/CategoriesList';



import PrivateRoute from './components/PrivateRoute';
import Layout from './components/Layout';

function App() {
  return (
    <Routes>
      {}
      <Route path="/" element={<Navigate to="/login" replace />} />

      {}
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Register />} />


      {}
      <Route element={<Layout />}>
        <Route
          path="/products"
          element={
            <PrivateRoute>
              <ProductsList />
            </PrivateRoute>
          }
        />

        <Route
          path="/orders"
          element={
            <PrivateRoute>
              <ComandesList />
            </PrivateRoute>
          }
        />

        <Route
          path="/clients"
          element={
            <PrivateRoute>
              <ClientsList />
            </PrivateRoute>
          }
        />

        <Route
          path="/proveidors"
          element={
            <PrivateRoute>
              <ProveidorsList />
            </PrivateRoute>
          }
        />

        <Route
          path="/categories"
          element={
            <PrivateRoute>
              <CategoriesList />
            </PrivateRoute>
          }
        />
      </Route>

      
    </Routes>
  );
}

export default App;
