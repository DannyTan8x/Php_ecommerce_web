import React, {useEffect, useState} from "react";
import { useFetcher, useParams } from "react-router-dom";
import axiosInstance from "../axiosConfig";

export default function UserDetail(){
    const {id} = useParams();
    const [user, setUser] = useState(null);

    useEffect(()=>{
        const fetchUser = async () =>{
            try{
                const response = await axiosInstance.get(`/user/read_one.php?id=${id}`);
                // console.log(response);
                setUser(response.data)
                // const jsonString = response.data.replace('connected', '');
                // console.log(jsonString)
                // const jsonData = JSON.parse(jsonString);
                // // console.log(jsonData);
                // setUser(jsonData);

            }catch(error){
                console.error(error)
            }
        };
        fetchUser();
    },[id])

    return(
        <div>
            {user ? (<div>
                <h1>{user.name}</h1>
                <p>Email:{user.email}</p>
            </div>):(<p>Loading ...</p>)}
        </div>
    );
}