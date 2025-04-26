'use client';
import Navbar from '../../components/Navbar';
import React from "react";
import ImageCarousel from '../../components/imageCarousel';
import Image from "next/image";

interface Styles {
    container: React.CSSProperties;
    title: React.CSSProperties;
    mainContent: React.CSSProperties;
    subTitle: React.CSSProperties;
    imageGrid: React.CSSProperties;
    branchCard: React.CSSProperties;
    branchDescription: React.CSSProperties;
    highlightImage: React.CSSProperties;
}

export default function Homepage(): JSX.Element {
    return (
        <div style={styles.container}>
            <Navbar />
            <main style={styles.mainContent}>
                {/* <h1 style={styles.title}>Don Macchiatos</h1> */}
                <h2 style={styles.subTitle}>DON MACCHIATOS DRINKS</h2>
                <ImageCarousel />
            </main>
        </div>
    );
}

const styles: Styles = {
    container: {
        fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
        backgroundColor: "#faf2e9",
        minHeight: "100vh",
        display: "flex",
        flexDirection: "row",
    },
    title: {
        fontSize: "3rem",
        fontWeight: "bold",
        textAlign: "center",
        color: "#4b3025",
        marginBottom: "20px",
        textTransform: "uppercase",
        letterSpacing: "3px",
        textShadow: "2px 2px 4px rgba(0, 0, 0, 0.2)", // Subtle shadow effect
        padding: "10px 0",
        display: "inline-block",
        marginTop: "20px",
    },
    mainContent: {
        flex: 1,
        padding: "20px",
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        marginTop: '6vh',
    },
    subTitle: {
        fontSize: "2rem",
        color: "#4b3025",
        fontWeight: "bold",
    },
    imageGrid: {
        display: "grid",
        gridTemplateColumns: "repeat(2, 1fr)",
        gap: "20px",
        width: "100%",
        maxWidth: "1200px",
    },
    branchCard: {
        backgroundColor: "#fff8e7",
        borderRadius: "12px",
        overflow: "hidden",
        boxShadow: "0px 4px 8px rgba(0, 0, 0, 0.1)",
        padding: "15px",
        textAlign: "center",
    },
    branchDescription: {
        marginTop: "10px",
        fontSize: "1rem",
        color: "#4b3025",
        fontWeight: "bold",
    },
    highlightImage: {
        borderRadius: "12px",
    },
};
