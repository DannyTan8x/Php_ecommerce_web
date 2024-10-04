import React, {useEffect, useState} from "react";
import axiosInstance from "../axiosConfig";
import { Link } from "react-router-dom";

export default function UserList(){
    const [users, setUsers] = useState([]);

    useEffect(() =>{
        const fetchUsers = async () =>{
            try {
                const response = await axiosInstance.get('/user/read.php');
                setUsers(response.data.records);
            } catch(error){
                console.error(error)
            }
        }
        fetchUsers();
    },[]);

    return(
        <div>
            <h1>User List</h1>
            <ul>
                {users.map(user => (
                    <li key={user.id}>
                        <Link to={`/user/${user.id}`}>
                            <h2>{user.name}</h2>
                            <p>{user.email}</p>
                        </Link>
                    </li>
                ))}
            </ul>
        </div>
    );

}