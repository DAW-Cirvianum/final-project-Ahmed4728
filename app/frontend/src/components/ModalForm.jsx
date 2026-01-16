import React, { useState } from 'react';
import { Modal, Button, Form } from 'react-bootstrap';
import { API_URL } from '../config';

export default function ModalForm({ entity, data, onClose }) {
  const [form, setForm] = useState(data || {});
  const token = localStorage.getItem('token');

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const method = data ? 'PUT' : 'POST';
    const url = data
      ? `${API_URL}/${entity}/${data.id}`
      : `${API_URL}/${entity}`;

    try {
      const res = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`
        },
        body: JSON.stringify(form)
      });
      if (!res.ok) throw new Error('Error al guardar');
      onClose();
    } catch (err) {
      alert(err.message);
    }
  };

  return (
    <Modal show onHide={onClose}>
      <Modal.Header closeButton>
        <Modal.Title>{data ? 'Editar' : 'Crear'} {entity}</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Form onSubmit={handleSubmit}>
          {Object.keys(form).map(key => (
            key !== 'id' && (
              <Form.Group key={key} className="mb-3">
                <Form.Label>{key}</Form.Label>
                <Form.Control
                  type="text"
                  name={key}
                  value={form[key] || ''}
                  onChange={handleChange}
                />
              </Form.Group>
            )
          ))}
          <Button type="submit">{data ? 'Guardar' : 'Crear'}</Button>
        </Form>
      </Modal.Body>
    </Modal>
  );
}
