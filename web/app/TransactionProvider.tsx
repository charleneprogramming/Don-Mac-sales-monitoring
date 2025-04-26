import { AppProps } from 'next/app';
import '../styles/globals.css';
import { TransactionProvider } from '@/context/transactioncontext';

export default function MyApp({ Component, pageProps }: AppProps): JSX.Element {
  return (
    <TransactionProvider>
      <Component {...pageProps} />
    </TransactionProvider>
  );
}
