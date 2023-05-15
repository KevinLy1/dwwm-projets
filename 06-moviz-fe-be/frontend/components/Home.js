import React, { useState, useEffect } from "react";
import styles from "../styles/Home.module.css";
import {faCircleXmark} from '@fortawesome/free-solid-svg-icons'
import 'antd/dist/antd.css'
import { Popover, Button } from "antd";

import Movie from "../components/Movie";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

export default function Home() {
  const [moviesList, setMoviesList] = useState([]);

  useEffect(() => {
    fetch('http://localhost:4000/movies')
    .then((res) => res.json())
    .then((data) => {
      let moviesListData = data.movies.map((movie) => {
        return {
          title : movie.title,
          poster : `https://image.tmdb.org/t/p/w500${movie.poster_path}`,
          voteAverage : movie.vote_average,
          voteCount : movie.vote_count,
          overview : movie.overview.substring(0,249)
        }
      });
      setMoviesList(moviesListData);
    });
  }, []);

  const [likedMovies, setLikedMovies] = useState([]);
  const updateLikedMovies = (movieTitle) => {
    const found = likedMovies.find(element => element == movieTitle)
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
        {element}
        <FontAwesomeIcon icon={faCircleXmark} onClick={() => updateLikedMovies(element)} />
      </span>
    );
  });

  const popoverContent = (
   <div className={styles.popoverContent}>{popoverMovies}</div>
  );
    
  const movies = moviesList.map((movie, i) => {
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
          <img src="logo.png" alt="logo" />
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
