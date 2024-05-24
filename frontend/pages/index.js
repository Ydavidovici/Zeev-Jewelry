import Head from 'next/head';
import Header from '../components/Header';
import Footer from '../components/Footer';
import styles from '../styles/Home.module.css';

export default function Home() {
  return (
      <div>
        <Head>
          <title>Zeev-Jewelry</title>
          <meta name="description" content="Luxury Jewelry and Watches" />
          <link rel="icon" href="/favicon.ico" />
        </Head>

        <Header />

        <main className={styles.main}>
          <h1 className={styles.title}>Welcome to Zeev-Jewelry</h1>
          {/* Add more components and content here */}
        </main>

        <Footer />
      </div>
  );
}
