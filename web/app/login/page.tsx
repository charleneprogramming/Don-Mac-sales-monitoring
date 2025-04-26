'use client';
import { useReducer, FormEvent } from 'react';
import { useRouter } from 'next/navigation';

interface Styles {
  wrapper: React.CSSProperties;
  imageContainer: React.CSSProperties;
  image: React.CSSProperties;
  container: React.CSSProperties;
  title: React.CSSProperties;
  header: React.CSSProperties;
  form: React.CSSProperties;
  inputGroup: React.CSSProperties;
  label: React.CSSProperties;
  input: React.CSSProperties;
  button: React.CSSProperties;
  error: React.CSSProperties;
  togglePassword: React.CSSProperties;
  buttonHover: React.CSSProperties;
  errorMessage: React.CSSProperties;
}

const styles: Styles = {
  wrapper: {
    display: 'flex',
    height: '100vh',
    width: '100%',
  },
  imageContainer: {
    flex: 1.5,
    backgroundColor: '#f5f5f5', // Optional background color
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
  },
  image: {
    width: '100%',
    height: '100%',
    objectFit: 'cover',
  },

  container: {
    flex: 1,
    display: 'flex',
    backgroundColor: '#faf2e9',
    flexDirection: 'column',
    justifyContent: 'center',
    alignItems: 'center',
    padding: '2rem',
  },
  title: {
    fontSize: '1.5rem',
    color: '#6f4e37', // Coffee brown
    marginBottom: '1.5rem',
    fontFamily:`'Lato', sans-serif`,
    textAlign: 'center',
  },
  header: {
    textAlign: 'center',
    marginBottom: '2rem',
    fontSize: '2rem',
    color: '#6f4e37',
    fontFamily: `'Lato', sans-serif`,
  },
  form: {
    display: 'flex',
    flexDirection: 'column',
    width: '100%',
    maxWidth: '400px',
    padding: '30px',
    borderRadius: '12px',
  
  },
  inputGroup: {
    marginBottom: '1.5rem',
  },
  label: {
    marginBottom: '0.5rem',
    fontWeight: 'bold',
    color: '#6f4e37',
    fontSize: '1rem',
  },
  input: {
    padding: '12px',
    border: '1px solid #d0c7c1',
    borderRadius: '6px',
    width: '100%',
    outline: 'none',
    fontSize: '1rem',
    fontFamily: `'Lato', sans-serif`,
    backgroundColor: '#fefcfb',
  },
  button: {
    padding: '12px',
    backgroundColor: '#6f4e37',
    color: 'white',
    border: 'none',
    borderRadius: '8px',
    cursor: 'pointer',
    fontSize: '1rem',
    fontWeight: 'bold',
    fontFamily: `'Lato', sans-serif`,
    transition: 'transform 0.2s, background-color 0.3s',
    boxShadow: '0 4px 10px rgba(0, 0, 0, 0.2)',
  },
  buttonHover: {
    transform: 'scale(1.05)',
    backgroundColor: '#5a3c2d',
  },
  error: {
    color: 'red',
    fontSize: '0.9rem',
    marginBottom: '1rem',
    textAlign: 'center',
    fontFamily: `'Lato', sans-serif`,
  },
  togglePassword: {
    marginTop: '10px',
    fontSize: '0.9rem',
    color: '#6f4e37',
    cursor: 'pointer',
    textAlign: 'right',
    textDecoration: 'underline',
  },
  errorMessage: {
    position: 'fixed',
    top: '20px',
    left: '50%',
    transform: 'translateX(-50%)',
    backgroundColor: '#ffebee',
    color: '#c62828',
    padding: '12px 24px',
    borderRadius: '8px',
    boxShadow: '0 2px 10px rgba(0, 0, 0, 0.1)',
    zIndex: 1000,
    fontFamily: `'Lato', sans-serif`,
    display: 'flex',
    alignItems: 'center',
    gap: '8px',
  },
};

interface FormState {
  username: string;
  password: string;
  showPassword: boolean;
  error: string;
  isLoading: boolean;
}

type Action =
  | { type: 'SET_FIELD'; field: keyof FormState; value: string | boolean }
  | { type: 'SET_ERROR'; error: string }
  | { type: 'SET_LOADING'; isLoading: boolean }
  | { type: 'RESET_FORM' };

const initialState: FormState = {
  username: '',
  password: '',
  showPassword: false,
  error: '',
  isLoading: false,
};

const reducer = (state: FormState, action: Action): FormState => {
  switch (action.type) {
    case 'SET_FIELD':
      return { ...state, [action.field]: action.value };
    case 'RESET_FORM':
      return initialState;
    default:
      return state;
  }
};

export default function LoginPage() {
  const [state, dispatch] = useReducer(reducer, initialState);
  const router = useRouter();

  const handleLogin = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    dispatch({ type: 'SET_ERROR', error: '' });
    dispatch({ type: 'SET_LOADING', isLoading: true });
    dispatch({ type: 'SET_ERROR', error: 'Attempting to log in...' });

    try {
      const loginResponse = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          username: state.username,
          password: state.password,
        }),
      });

      if (!loginResponse.ok) {
        dispatch({ type: 'SET_ERROR', error: 'Username or password is incorrect' });
        return;
      }

      const loginData = await loginResponse.json();

      if (loginData.token) {
        dispatch({ type: 'SET_ERROR', error: 'Login successful! Redirecting...' });
        localStorage.setItem('token', loginData.token);
        localStorage.setItem('userId', loginData.user.id.toString());
        localStorage.setItem('user', JSON.stringify(loginData.user));
        localStorage.setItem('isAuthenticated', 'true');
        setTimeout(() => {
          router.push('/homepage');
        }, 1000);
      } else {
        throw new Error('Authentication failed');
      }
    } catch (error) {
      console.error('Login error:', error);
      dispatch({ type: 'SET_ERROR', error: 'Login failed. Please try again.' });
    } finally {
      dispatch({ type: 'SET_LOADING', isLoading: false });
    }
  };

  return (
    <>
      {state.error && (
        <div style={styles.errorMessage}>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
          </svg>
          {state.error}
        </div>
      )}

      <div style={styles.wrapper}>
      {/* Left Side: Image */}
      <div style={styles.imageContainer}>
        <img
          src="/images/background-2.png"
          alt="Welcome"
          style={styles.image}
        />
      </div>

  {/* Right Side: Form Container */}
  <div style={styles.container}>
    {/* <h1 style={styles.title}>Don Macchiatos Sales Interface System</h1>
    <h2 style={styles.header}>Welcome Back!</h2> */}
    <form onSubmit={handleLogin} style={styles.form}>
      <div style={styles.inputGroup}>
        <div style={styles.title}>
        <h4 className='text-xl'><strong>Don Macchiatos Cashier</strong></h4>
        <h2 className='text-4xl mt-5'><strong>Sign in</strong></h2>
        </div>
        <label style={styles.label} htmlFor="username">
          Username
        </label>
        <input
          id="username"
          type="text"
          value={state.username}
          onChange={(e) =>
            dispatch({ type: 'SET_FIELD', field: 'username', value: e.target.value })
          }
          required
          style={styles.input}
        />
      </div>
      <div style={styles.inputGroup}>
        <label style={styles.label} htmlFor="password">
          Password
        </label>
        <input
          id="password"
          type="password"
          value={state.password}
          onChange={(e) =>
            dispatch({ type: 'SET_FIELD', field: 'password', value: e.target.value })
          }
          required
          style={styles.input}
        />
      </div>
      <button type="submit" style={styles.button}>
        SIGN IN
      </button>
    </form>
  </div>
</div>
    </>
  );
}
