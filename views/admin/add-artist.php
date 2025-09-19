<?php include 'include/head.php'; ?>
<?php include 'include/head-bar.php'; ?>
<?php
require_once 'model/ArtistModel.php';
require_once 'model/MusicDetailsModel.php';

// Database connection
$database = new Database();
$pdo = $database->connect();

// Instantiate the models
$artistModel = new ArtistModel($pdo);
$songModel = new MusicDetailsModel($pdo);

// Fetch all songs for the dropdown
$songs = $songModel->getAllSongs();

// Initialize variables
$artistId = $_GET['id'] ?? null;
$artistData = $artistId ? $artistModel->getArtistById($artistId) : null;

// Check if song_id is a string and convert it to an array; if it's already an array, use it directly
$selectedSongs = [];
if ($artistData && isset($artistData['song_id'])) {
    $selectedSongs = is_string($artistData['song_id']) ? explode(',', $artistData['song_id']) : $artistData['song_id'];
}
$isEdit = $artistData !== null;

?>

<div class="inner-wrapper">
    <?php include 'include/side-bar.php'; ?>
    <section role="main" class="content-body content-body-modern">
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                        </div>
                        <h2 class="card-title"><?= $isEdit ? 'Edit Artist' : 'Add Artist'; ?></h2>
                    </header>
                    <div class="card-body">
                        <form action="<?= $isEdit ? 'edit-artist?id=' . $artistId : 'add-artist'; ?>" method="post"
                            class="form-horizontal form-bordered" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'add'; ?>">
                            <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $artistId; ?>">
                            <?php endif; ?>

                            <!-- Artist Name -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="artistName">Artist
                                    Name</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="artistName" name="artist_name"
                                        value="<?= htmlspecialchars($artistData['artist_name'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <!-- Song Selection (Multiple Select Dropdown) -->
                            <div class="form-group row pb-3">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="selectSong">Select
                                    Songs</label>
                                <div class="col-lg-6">
                                    <span class="multiselect-native-select">
                                        <select class="form-control" id="selectSong" name="song_name[]"
                                            multiple="multiple" data-plugin-multiselect=""
                                            data-plugin-options="{ &quot;maxHeight&quot;: 200, &quot;enableCaseInsensitiveFiltering&quot;: true }">
                                            <optgroup label="Select Songs">
                                                <?php foreach ($songs as $song): ?>
                                                <option value="<?= htmlspecialchars($song['song_id']); ?>"
                                                    <?= in_array($song['song_id'], $selectedSongs) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($song['song_name']); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        </select>

                                    </span>
                                </div>
                            </div>


                            <!-- Artist Image -->
                            <div class="form-group row pb-4">
                                <label class="col-lg-3 control-label text-lg-end pt-2" for="artistImage">Artist
                                    Image</label>
                                <div class="col-lg-6">
                                    <input type="file" id="artistImage" name="artist_image" class="form-control-file">
                                    <?php if ($isEdit && !empty($artistData['artist_image'])) : ?>
                                    <img src="../uploads/admin/artist/<?= htmlspecialchars($artistData['artist_image']); ?>"
                                        alt="Artist Image" class="image-preview mt-2" style="max-width: 200px;">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-9 col-lg-offset-3">
                                    <button type="submit" class="btn btn-primary">
                                        <?= $isEdit ? 'Update Artist' : 'Add Artist'; ?>
                                    </button>
                                    <a href="manage-artist" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>

<?php include 'include/footer.php'; ?>