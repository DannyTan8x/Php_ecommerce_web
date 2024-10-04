import React, { useState } from "react";
import axiosInstance from "../axiosConfig";
import { jwtDecode } from "jwt-decode";

export default function Admin() {
  const [name, setName] = useState("");
  const [description, setDescription] = useState("");
  const [price, setPrice] = useState("");
  const [images, setImages] = useState([]);
  const [message, setMessage] = useState("");

  const handleImageChange = (e) => {
    setImages([...e.target.files]);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData();
    formData.append("name", name);
    formData.append("description", description);
    formData.append("price", price);
    // console.log(images);
    images.forEach((image, index) => {
      console.log(image, index);
      formData.append(`images[${index}]`, image);
    });

    try {
      const response = await axiosInstance.post(
        `/product/create.php`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
            // 'Content-Type': 'application/json',
          },
        }
      );
      setMessage("Product created successfully");
      // console.log(response.data);
    } catch (error) {
      console.error("There was an error creating the product!", error);
      setMessage("Failed to create product");
    }
  };

  // const updateProduct = async (product) =>{
  //     try{
  //         const formData = new FormData();
  //         formData.append('id', product.id);
  //         formData.append('name', product.name);
  //         formData.append('description', product.description);
  //         formData.append('price', product.price);
  //         product.photos.forEach((photo, index) =>{
  //             formData.append('photos', photo);
  //         });

  //         const response = await axiosInstance.put('/product/update.php', formData, {
  //             headers:{
  //                 'Content-Type': 'multipart/form-data'
  //             }
  //         })
  //         console.log(response.data);
  //     }catch(error){
  //         console.error(error)
  //     }
  // }

  return (
    <div>
      <h1>Admin Panel</h1>
      <h2>Create Product</h2>
      <form onSubmit={handleSubmit}>
        <div>
          <label>Name:</label>
          <input
            type="text"
            name="name"
            value={name}
            onChange={(e) => setName(e.target.value)}
            required
          />
        </div>
        <div>
          <label>Description:</label>
          <textarea
            name="description"
            value={description}
            onChange={(e) => setDescription(e.target.value)}
            required
          />
        </div>
        <div>
          <label>Price:</label>
          <input
            type="number"
            name="price"
            value={price}
            onChange={(e) => setPrice(e.target.value)}
            required
          />
        </div>
        <div>
          <label>Photo:</label>
          <input
            type="file"
            multiple
            name="images"
            onChange={handleImageChange}
          />
        </div>
        <button type="submit">Create Product</button>
      </form>
      {message && <p>{message}</p>}
    </div>
  );
}
