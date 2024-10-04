import React, { useEffect, useState } from "react";
import axiosInstance from "../axiosConfig";
import { Link } from "react-router-dom";

export default function ProductList() {
  const [products, setProducts] = useState([]);

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const response = await axiosInstance.get("/product/read.php");
        // console.log(response);
        setProducts(response.data.records);
        //當data 回傳 “connected" 用下面方式處理獲取 資料
        // const jsonString = response.data.replace('connected', '');
        // const jsonData = JSON.parse(jsonString);
        // setProducts(jsonData.records);
        // console.log(typeof jsonData, jsonData);
      } catch (error) {
        console.error(error);
      }
    };

    fetchProducts();
  }, []); // blank [] for initialize fetch data

  return (
    <div>
      <h2>Products List</h2>
      {products.length > 0 ? (
        products.map((product) => (
          <div key={product.id}>
            <Link to={`/${product.id}`}>
              <h3>{product.name}</h3>
            </Link>
            <p>{product.description}</p>
            <p>${product.price}</p>
            <div>
              {product.images.map((image, index) => (
                <img
                  key={index}
                  src={image.image_url}
                  alt={`Product ${product.id}`}
                  style={{ width: "400px" }}
                />
              ))}
            </div>
          </div>
        ))
      ) : (
        <p>No products found.</p>
      )}
    </div>
  );
}
