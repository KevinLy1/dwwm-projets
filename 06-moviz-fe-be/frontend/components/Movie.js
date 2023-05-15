import React, { useState } from 'react';
import Image from 'next/image';
import styles from '../styles/Movie.module.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faStar, faVideo, faHeart } from '@fortawesome/free-solid-svg-icons';

function Movie(props) {
  const stars = [];
  for(let i = 0; i < 10 ; i++) {
    let style = {};
    if (i < Math.floor(props.voteAverage)) style = { color : '#f1c40f' };
    stars.push(<FontAwesomeIcon icon={faStar} key={i} style={style} />)
  }

  let [personalNote, setPersonalNote] = useState(0);
  const personalStars = [];

  for(let i = 0; i < 10 ; i++) {
    let style = { cursor : 'pointer' };
    if (i < personalNote) style = { color : '#2196f3', cursor : 'pointer' };
    personalStars.push(<FontAwesomeIcon icon={faStar} onClick={ () => setPersonalNote(i+1) } key={i} style={style}/>);
  }

  let [watchCount, setWatchCount] = useState(0);

  const handleMovie = () => {
    setWatchCount(watchCount++);
  };
  
  let videoIconStyle = {cursor : 'pointer'};
  if (watchCount > 0) videoIconStyle = {cursor : 'pointer', color: '#e74c3c'};

  const handleLike = () => {
    props.updateLikedMovies(props.title);
  }

  let heartIconStyle = {cursor : 'pointer'};
  if(props.isLiked) heartIconStyle = {cursor : 'pointer', color: '#e74c3c'};

  return (
    <div className={styles.card}>
      <Image className={styles.image} src={props.poster} alt={props.title} width={150} height={310} />
      <div className={styles.textContainer}>
        <span className={styles.name}>{props.title}</span>
        <p className={styles.description}>{props.overview}</p>
      </div>
      <div className={styles.vote}>{stars} {props.voteAverage} ({props.voteCount})</div>
      <div className={styles.vote}>{personalStars} ({personalNote})</div>
      <div className={styles.vote}><FontAwesomeIcon icon={faVideo} onClick={() => handleMovie()} style={videoIconStyle}/> {watchCount}</div>
      <div className={styles.vote}><FontAwesomeIcon icon={faHeart} onClick={() => handleLike()} style={heartIconStyle} /></div>
  
    </div>
  )
}

export default Movie;
