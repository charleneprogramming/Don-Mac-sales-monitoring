// _app.tsx
import { AppProps } from 'next/app';
import '@fortawesome/fontawesome-free/css/all.min.css';
import { TransactionProvider } from '@/context/transactioncontext'; // Import TransactionProvider

function MyApp({ Component, pageProps }: AppProps): JSX.Element {
    return (
        <TransactionProvider>
            <Component {...pageProps} />
        </TransactionProvider>
    );
}

export default MyApp;
