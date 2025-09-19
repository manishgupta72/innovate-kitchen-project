<?php

class ArtistModel
{
    protected $pdo;

    public function __construct($pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO object provided");
        }
        $this->pdo = $pdo;
    }

    // Fetch all artists with song names
    // Fetch all artists with song details
    public function getAllArtists()
    {
        $stmt = $this->pdo->prepare("
             SELECT artist.*, 
                 GROUP_CONCAT(music.song_name ORDER BY music.song_name SEPARATOR ', ') AS song_names,
                 GROUP_CONCAT(music.song_singer_name ORDER BY music.song_name SEPARATOR ', ') AS singer_names,
                 GROUP_CONCAT(music.lyrics_writer_name ORDER BY music.song_name SEPARATOR ', ') AS lyrics_writers,
                 GROUP_CONCAT(music.song_composer_name ORDER BY music.song_name SEPARATOR ', ') AS composers,
                 GROUP_CONCAT(music.song_music_name ORDER BY music.song_name SEPARATOR ', ') AS music_directors,
                 GROUP_CONCAT(music.genre ORDER BY music.song_name SEPARATOR ', ') AS genres
             FROM artist
             LEFT JOIN song_details AS music ON FIND_IN_SET(music.song_id, artist.song_id) > 0
             GROUP BY artist.artist_id
             ORDER BY artist.created_at DESC
         ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Fetch artist by ID
    public function getArtistById($artistId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM artist WHERE artist_id = :artistId");
        $stmt->bindParam(':artistId', $artistId, PDO::PARAM_INT);
        $stmt->execute();
        $artist = $stmt->fetch(PDO::FETCH_ASSOC);

        // Convert song_id to an array if it exists
        if ($artist && !empty($artist['song_id'])) {
            $artist['song_id'] = explode(',', $artist['song_id']);
        }

        return $artist;
    }

    // Add a new artist
    public function addArtist($artistName, $songIds, $artistImage)
    {
        // Check if $songIds is an array; if so, convert it to a string
        $songIdStr = is_array($songIds) ? implode(',', $songIds) : $songIds;

        $sql = "INSERT INTO artist (artist_name, song_id, artist_image, created_at) 
                VALUES (:artistName, :songId, :artistImage, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':artistName' => $artistName,
            ':songId' => $songIdStr,
            ':artistImage' => $artistImage
        ]);
    }

    // Update an existing artist
    public function updateArtist($artistId, $artistName, $songIds, $artistImage)
    {
        // Check if $songIds is an array; if so, convert it to a string
        $songIdStr = is_array($songIds) ? implode(',', $songIds) : $songIds;

        $sql = "UPDATE artist SET
                artist_name = :artistName,
                song_id = :songId,
                artist_image = CASE WHEN :artistImage = '' THEN artist_image ELSE :artistImage END,
                updated_at = NOW()
            WHERE artist_id = :artistId";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':artistId' => $artistId,
            ':artistName' => $artistName,
            ':songId' => $songIdStr,
            ':artistImage' => $artistImage
        ]);
    }

    // Delete an artist by ID
    public function deleteArtist($artistId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM artist WHERE artist_id = :artistId");
        $stmt->bindParam(':artistId', $artistId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}