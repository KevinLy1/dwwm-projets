import React, { useState } from "react";
import Movie from "./Movie";
import styles from '../styles/Home.module.css';
import {faCircleXmark} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import 'antd/dist/antd.min.css';
import { Popover, Button } from "antd";

export default function Home() {
  const moviesData = [
    {
      title: 'Forrest Gump',
      poster: '../src/assets/images/forrestgump.jpg',
      voteAverage: 9.2,
      voteCount: 22_705,
      overview:
        'A man with a low IQ has accomplished great things in his life and been present during significant historic events—in each case.',
    },
    {
      title: 'The Dark Knight',
      poster: '../src/assets/images/thedarkknight.jpg',
      voteAverage: 8.5,
      voteCount: 27_547,
      overview:
        'Batman raises the stakes in his war on crime and sets out to dismantle the remaining criminal organizations that plague the streets.',
    },
    {
      title: 'Your name',
      poster: '../src/assets/images/yourname.jpg',
      voteAverage: 8.5,
      voteCount: 8_691,
      overview:
        'High schoolers Mitsuha and Taki are complete strangers living separate lives. But one night, they suddenly switch places.',
    },
    {
      title: 'Iron Man',
      poster: '../src/assets/images/ironman.jpg',
      voteAverage: 7.6,
      voteCount: 22_7726,
      overview:
        'After being held captive in an Afghan cave, billionaire engineer Tony Stark creates a unique weaponized suit of armor to fight evil.',
    },
    {
      title: 'Inception',
      poster: '../src/assets/images/inception.jpg',
      voteAverage: 8.4,
      voteCount: 31_546,
      overview:
        'Cobb, a skilled thief who commits corporate espionage by infiltrating the subconscious of his targets is offered a chance to regain his old life.',
    },
  ];
  
  const [likedMovies, setLikedMovies] = useState([]);
  const updateLikedMovies = (movieTitle) => {
    const found = likedMovies.find(element => element === movieTitle)
    if (!found) {
      setLikedMovies([...likedMovies, movieTitle]);
    }
    else {
      setLikedMovies(likedMovies.filter(element => element !== movieTitle))
    }
  }

  const popoverMovies = likedMovies.map((element, i) => {
    return (
      <span key={i}>
        {element} <FontAwesomeIcon icon={faCircleXmark} onClick={() => updateLikedMovies(element)} />
      </span>
    );
  });

  const popoverContent = (
   <div className={styles.popoverContent}>{popoverMovies}</div>
  );
    
  const movies = moviesData.map((movie, i) => {
    return (
      <Movie
        title={movie.title}
        poster={movie.poster}
        voteAverage={movie.voteAverage}
        voteCount={movie.voteCount}
        overview={movie.overview}
        updateLikedMovies={updateLikedMovies}
        isLiked={likedMovies.includes(movie.title)}
        key={i}
      />
    );
  });

  return (
    <div className={styles.main}>
      <div className={styles.header}>
        <div className={styles.logoContainer}>
          <img src="../src/assets/images/logo.png" alt="logo" />
          <p className={styles.title}>MOVIZ</p>
        </div>
        <Popover title="Liked Movies" content={popoverContent} trigger="click">
          <Button>❤️ {likedMovies.length} movies</Button>
        </Popover>
      </div>
      <div className={styles.release}>LAST RELEASES</div>
      <div className={styles.moviesContainer}>{movies}</div>
    </div>
  );
}
