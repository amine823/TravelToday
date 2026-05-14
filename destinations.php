<?php
$pageTitle = "Destinations - TravelToday";
include 'header.php';
require_once 'config.php';

$type_filter = isset($_GET['type']) ? $_GET['type'] : 'all';
$prix_max = isset($_GET['prix_max']) ? (int)$_GET['prix_max'] : 5000;

$conn = getConnection();
$sql = "SELECT * FROM destinations WHERE 1=1";

if ($type_filter != 'all') {
    $type_filter = $conn->real_escape_string($type_filter);
    $sql .= " AND type_voyage = '$type_filter'";
}

$sql .= " AND prix_depart <= $prix_max";
$sql .= " ORDER BY nom ASC";

$result = $conn->query($sql);
?>

<main>
    <section class="page-header">
        <div class="container">
            <h1>Our Destinations</h1>
            <p>Explore the world with TravelToday</p>
        </div>
    </section>

    <section class="filters-section">
        <div class="container">
            <form method="GET" action="destinations.php" id="filterForm" class="filters-form">
                <div class="filter-group">
                    <label for="type">Type of voyage:</label>
                    <select name="type" id="type" onchange="this.form.submit()">
                        <option value="all" <?php echo $type_filter == 'all' ? 'selected' : ''; ?>>All Types</option>
                        <option value="famille" <?php echo $type_filter == 'famille' ? 'selected' : ''; ?>>Family</option>
                        <option value="romantique" <?php echo $type_filter == 'romantique' ? 'selected' : ''; ?>>Romantic</option>
                        <option value="aventure" <?php echo $type_filter == 'aventure' ? 'selected' : ''; ?>>Adventure</option>
                        <option value="plage" <?php echo $type_filter == 'plage' ? 'selected' : ''; ?>>Plage</option>
                        <option value="culture" <?php echo $type_filter == 'culture' ? 'selected' : ''; ?>>Culture</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="prix_max">Budget maximum: <span id="budgetValue"><?php echo $prix_max; ?> TND</span></label>
                    <input type="range" name="prix_max" id="prix_max" min="0" max="5000" step="100" value="<?php echo $prix_max; ?>" oninput="updateBudget(this.value)">
                </div>
                <button type="submit" class="filter-button">Filter</button>
                <a href="destinations.php" class="reset-button">Reset</a>
            </form>
        </div>
    </section>

    <section class="destinations-section">
        <div class="container">
            <div class="results-info">
                <p><?php echo $result->num_rows; ?> destination(s) found</p>
            </div>
            <div class="destinations-grid">
                <?php 
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()): 
                        $prix_final = $row['promotion'] ? $row['prix_depart'] * (1 - $row['pourcentage_promo']/100) : $row['prix_depart'];
                ?>
                <div class="destination-card" data-type="<?php echo $row['type_voyage']; ?>">
                    <?php if ($row['promotion']): ?>
                    <div class="promo-badge">-<?php echo $row['pourcentage_promo']; ?>%</div>
                    <?php endif; ?>
                    <div class="destination-image" style="background-image: url('images/<?php echo $row['image']; ?>');">
                        <div class="destination-overlay">
                            <a href="#" class="view-details" onclick="showDestinationDetails(<?php echo $row['id']; ?>); return false;">
                                <i class="fas fa-search-plus"></i> View Details
                            </a>
                        </div>
                    </div>
                    <div class="destination-content">
                        <div class="destination-header">
                            <h3><?php echo htmlspecialchars($row['nom']); ?></h3>
                            <span class="destination-type"><?php echo ucfirst($row['type_voyage']); ?></span>
                        </div>
                        <p class="destination-location">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['pays']); ?>
                        </p>
                        <p class="destination-description">
                            <?php echo substr(htmlspecialchars($row['description']), 0, 120) . '...'; ?>
                        </p>
                        <div class="destination-footer">
                            <div class="price-container">
                                <?php if ($row['promotion']): ?>
                                    <span class="old-price"><?php echo number_format($row['prix_depart'], 2); ?> TND</span>
                                <?php endif; ?>
                                <span class="price">À partir de <?php echo number_format($prix_final, 2); ?> TND</span>
                            </div>
                            <a href="reservation.php?destination_id=<?php echo $row['id']; ?>" class="book-button">Reserve</a>
                        </div>
                    </div>
                </div>
                <?php 
                    endwhile;
                } else {
                    echo '<p class="no-results">No destination match your criteria.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <div id="destinationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modalContent">
            </div>
        </div>
    </div>
</main>

<?php 
closeConnection($conn);
include 'footer.php'; 
?>
