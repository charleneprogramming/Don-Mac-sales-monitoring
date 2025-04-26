'use client'

import React, { useState } from 'react';
import { useRouter } from 'next/navigation'; // Updated import for App Router

interface User {
    username: string;
    password: string;
}

interface FormData {
    username: string;
    password: string;
}

interface Styles {
    [key: string]: React.CSSProperties;
}

export default function CreateUser() {
    const router = useRouter();

    const [users, setUsers] = useState<User[]>([]);
    const [formData, setFormData] = useState<FormData>({
        username: '',
        password: '',
    });
    const [isEditing, setIsEditing] = useState<boolean>(false);
    const [editingUserId, setEditingUserId] = useState<number | null>(null);

    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setFormData({ ...formData, [name]: value });
    };

    const handleCreateUser = () => {
        if (!formData.username || !formData.password) {
            alert('Please fill in all fields');
            return;
        }

        if (isEditing && editingUserId !== null) {
            setUsers((prevUsers) =>
                prevUsers.map((user, index) =>
                    index === editingUserId ? { ...formData } : user
                )
            );
            setIsEditing(false);
            setEditingUserId(null);
        } else {
            setUsers([...users, formData]);
        }

        setFormData({ username: '', password: '' });
    };

    const handleEditUser = (index: number) => {
        setEditingUserId(index);
        setFormData(users[index]);
        setIsEditing(true);
    };

    const handleDeleteUser = (index: number) => {
        setUsers((prevUsers) => prevUsers.filter((_, i) => i !== index));
    };

    const handleBackClick = () => {
        router.back();
    };

    return (
        <div style={styles.page}>
            <div style={styles.header}>
                <button onClick={handleBackClick} style={styles.backButton}>
                    ← Back
                </button>
                <h1 style={styles.title}>Create User</h1>
            </div>
            <div style={styles.flex}>
                <div style={styles.form}>
                    <input
                        type="text"
                        name="username"
                        placeholder="Username"
                        value={formData.username}
                        onChange={handleInputChange}
                        style={styles.input}
                    />
                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        value={formData.password}
                        onChange={handleInputChange}
                        style={styles.input}
                    />
                    <button onClick={handleCreateUser} style={styles.createButton}>
                        {isEditing ? 'Update User' : 'Create User'}
                    </button>
                </div>
                <div style={styles.tableContainer}>
                    <table style={styles.table}>
                        <thead>
                            <tr>
                                <th style={styles.th}>Username</th>
                                <th style={styles.th}>Password</th>
                                <th style={styles.th}>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {users.map((user, index) => (
                                <tr key={index} style={styles.tr}>
                                    <td style={styles.td}>{user.username}</td>
                                    <td style={styles.td}>******</td>
                                    <td style={styles.td}>
                                        <button
                                            onClick={() => handleEditUser(index)}
                                            style={styles.editButton}
                                        >
                                            ✏️
                                        </button>
                                        <button
                                            onClick={() => handleDeleteUser(index)}
                                            style={styles.deleteButton}
                                        >
                                            🗑️
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}

const styles: Styles = {
    page: {
        minHeight: '100vh',
        display: 'flex',
        flexDirection: 'column',
        padding: '20px',
        backgroundColor: '#f9f9f9',
        boxSizing: 'border-box',
    },
    header: {
        marginBottom: '20px',
        display: 'flex',
        alignItems: 'center',
    },
    backButton: {
        padding: '10px',
        backgroundColor: '#4b3025',
        color: 'white',
        border: 'none',
        borderRadius: '8px',
        cursor: 'pointer',
        fontWeight: 'bold',
        fontSize: '14px',
        marginRight: '10px',
        transition: 'background-color 0.2s ease-in-out',
    },
    title: {
        fontSize: '24px',
        color: '#4b3025',
        fontWeight: 'bold',
    },
    flex: {
        display: 'flex',
        flexDirection: 'column',
        gap: '20px',
        flexGrow: 1,
    },
    form: {
        display: 'flex',
        flexDirection: 'column',
        gap: '10px',
    },
    input: {
        padding: '10px',
        borderRadius: '6px',
        border: '1px solid #ccc',
        fontSize: '14px',
        outline: 'none',
    },
    createButton: {
        padding: '10px',
        backgroundColor: '#4CAF50',
        color: 'white',
        border: 'none',
        borderRadius: '6px',
        fontSize: '16px',
        cursor: 'pointer',
        transition: 'background-color 0.2s',
    },
    tableContainer: {
        flexGrow: 1,
        overflowX: 'auto',
    },
    table: {
        width: '100%',
        borderCollapse: 'collapse',
    },
    th: {
        textAlign: 'left',
        padding: '10px',
        backgroundColor: '#f0eae4',
        color: '#4b3025',
        fontWeight: 'bold',
    },
    tr: {
        borderBottom: '1px solid #ccc',
    },
    td: {
        padding: '10px',
    },
    editButton: {
        backgroundColor: '#FFC107',
        color: 'white',
        border: 'none',
        borderRadius: '6px',
        cursor: 'pointer',
        padding: '6px',
        marginRight: '6px',
    },
    deleteButton: {
        backgroundColor: '#F44336',
        color: 'white',
        border: 'none',
        borderRadius: '6px',
        cursor: 'pointer',
        padding: '6px',
    },
};
