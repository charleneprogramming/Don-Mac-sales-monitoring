import Link from 'next/link';
import { useState } from 'react';
import { CSSProperties } from 'react';
import '@fortawesome/fontawesome-free/css/all.min.css';

interface Styles {
  navbar: CSSProperties;
  avatarContainer: CSSProperties;
  avatar: CSSProperties;
  avatarName: CSSProperties;
  userName: CSSProperties;
  list: CSSProperties;
  listItem: CSSProperties;
  link: CSSProperties;
  linkText: CSSProperties;
  icon: CSSProperties;
  linkHover: CSSProperties;
}

export default function Navbar(): JSX.Element {

  return (
    <div style={styles.navbar}>
       {/* Avatar Section */}
       <div style={styles.avatarContainer}>
        <img
          src="/images/donmac.jpg" // Replace with the actual avatar image path
          alt="Admin Avatar"
          style={styles.avatar}
        />
        <h3 className='tracking-[.15em]' style={styles.avatarName}>CASHIER {}</h3>
      </div>



      {/* Menu Section */}
      <ul style={styles.list}>
        {/* <li style={styles.listItem}>
          <Link href="/dashboard" style={styles.link}>
            <i style={styles.icon} className="fas fa-tachometer-alt"></i>
            {!collapsed && <span style={styles.linkText}>Dashboard</span>}
          </Link>
        </li> */}
        {/* <li style={styles.listItem}>
          <Link href="/product" style={styles.link}>
            <i style={styles.icon} className="fas fa-box"></i>
            {!collapsed && <span style={styles.linkText}>Products</span>}
          </Link>
        </li> */}
        <li style={styles.listItem}>
          <Link href="/homepage" style={styles.link}>
            <i style={styles.icon} className="fa-solid fa-house-user"></i>
            <span style={styles.linkText}>HOME</span>
          </Link>
        </li>
        <li style={styles.listItem}>
          <Link href="/sale" style={styles.link}>
            <i style={styles.icon} className="fas fa-chart-line"></i>
            <span style={styles.linkText}>SALES</span>
          </Link>
        </li>
        <li style={styles.listItem}>
          <Link href="/transaction" style={styles.link}>
            <i style={styles.icon} className="fas fa-history"></i>
            <span style={styles.linkText}>TRANSACTION</span>
          </Link>
        </li>
        <li style={styles.listItem}>
          <Link href="/login" style={styles.link}>
            <i style={styles.icon} className="fas fa-sign-out-alt"></i>
          </Link>
        </li>
      </ul>
    </div>
  );
}

const styles: Styles = {
  navbar: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: '#9a4c2e',
    color: '#fff',
    padding: '10px 20px',
    height: '60px',
    position: 'fixed',
    top: 0,
    left: 0,
    right: 0,
    zIndex: 1000,
  },
  avatarContainer: {
    display: 'flex',
    alignItems: 'center',
    gap: '20px',
  },
  avatar: {
    width: '40px',
    height: '40px',
    borderRadius: '50%',
    objectFit: 'cover',
  },
  avatarName: {
    fontSize: '1.5rem',
  },
  userName:{
    fontSize: '1rem',
    color: '#fff',
    textTransform: 'uppercase',
    letterSpacing: '2px',
    fontWeight: 'bold',
  },
  list: {
    display: 'flex',
    alignItems: 'center',
    gap: '10vh',
    listStyle: 'none',
    margin: 0,
    padding: 0,
  },
  listItem: {
    color: '#fff',
    textDecoration: 'none',
    fontSize: '16px',
    cursor: 'pointer'
  },
  link: {
    display: 'flex',
    alignItems: 'center',
    padding: '10px 15px',
    color: '#ecf0f1',
    textDecoration: 'none',
    fontSize: '1rem',
    transition: 'background-color 0.3s ease, color 0.3s ease',
  },
  linkText: {
    marginLeft: '10px',
    fontSize: '1rem',
  },
  icon: {
    fontSize: '1.2rem',
  },
  linkHover: {
    backgroundColor: '#34495e',
    color: '#1abc9c',
  },
};
