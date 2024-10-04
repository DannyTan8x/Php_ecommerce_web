import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import axiosInstance from "../axiosConfig";

export default function ProductDetail() {
  const { id } = useParams(); //在 APP.JS 定義:id 會導入Params， 用解析方式把 id 取出來。
  const [product, setProduct] = useState(null);

  useEffect(() => {
    const fetchProduct = async () => {
      try {
        const response = await axiosInstance.get(
          `/product/read_one.php?id=${id}`
        );
        setProduct(response.data);
        // const jsonString = response.data.replace('connected', '');
        // console.log(jsonString)
        // const jsonData = JSON.parse(jsonString);
        // console.log(jsonData);
        // console.log(typeof product);
        // console.log(product.name);
      } catch (error) {
        console.error(error);
      }
    };

    fetchProduct();
  }, [id]);

  return (
    <div>
      {product ? (
        <div>
          <h1>{product.name}</h1>
          {product.images.map((image, index) => (
            <img
              key={index}
              src={image.image_url}
              alt={product.nam}
              style={{ width: "200px", margin: "10px" }}
            />
          ))}
          <p>{product.description}</p>
          <p>${product.price}</p>
        </div>
      ) : (
        <p>Loading ...</p>
      )}
    </div>
  );
}
