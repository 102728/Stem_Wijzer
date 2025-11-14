<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($partij['partij_naam']) ?> - StemWijzer</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body class="partijBody">
    <?php include 'views/navbar.php'; ?>
    
    <div id="toast" class="toast"></div>
    
    <!-- Hero -->
    <div class="detail-hero">
        <a href="partijen.php" class="detail-back">Terug naar overzicht</a>
    </div>

    <!-- Content -->
    <div class="detail-content">
        <div class="detail-left">
            <div class="detail-img">
                <?php if (!empty($partij['bestand_data'])): ?>
                    <img src="data:<?= htmlspecialchars($partij['bestand_type']) ?>;base64,<?= base64_encode($partij['bestand_data']) ?>" 
                         alt="<?= htmlspecialchars($partij['partij_naam']) ?>">
                <?php else: ?>
                    <div class="no-img">Geen foto beschikbaar</div>
                <?php endif; ?>
            </div>
            <div class="detail-party-info">
                <h1><?= htmlspecialchars($partij['partij_naam']) ?></h1>
                <p class="detail-voorzitter">Voorzitter: <?= htmlspecialchars($partij['voorzitter']) ?></p>
                <div class="detail-vote-count">
                    <span class="vote-num"><?= $partij['aantalStem'] ?? 0 ?></span>
                    <span class="vote-text">stemmen</span>
                </div>
            </div>
        </div>

        <div class="detail-right">
            <div class="detail-block">
                <h3>Ideologie</h3>
                <p><?= nl2br(htmlspecialchars($partij['ideologie'])) ?></p>
            </div>
            
            <div class="detail-block">
                <h3>Thema's</h3>
                <div class="detail-themas">
                    <?php 
                    $themas = explode(',', $partij['themas']);
                    foreach ($themas as $thema): 
                    ?>
                        <span class="thema-tag"><?= htmlspecialchars(trim($thema)) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="detail-actions">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <p class="action-message">Log in om te kunnen stemmen</p>
                    <a href="index.php" class="detail-login-btn">Inloggen</a>
                <?php elseif ($isOwnParty): ?>
                    <p class="action-message">Dit is jouw partij</p>
                    <a href="profiel.php" class="detail-edit-btn">Partij Aanpassen</a>
                <?php elseif ($userVotedForThisParty): ?>
                    <p class="action-message success">Je hebt op deze partij gestemd</p>
                    <button type="button" class="detail-remove-btn" onclick="showConfirmModal('remove')">Stem Verwijderen</button>
                <?php elseif ($userHasVoted): ?>
                    <p class="action-message warning">Je hebt al gestemd op een andere partij</p>
                    <p class="action-hint">Verwijder eerst je stem om opnieuw te kunnen stemmen</p>
                <?php else: ?>
                    <button type="button" class="detail-stem-btn" onclick="showConfirmModal('vote')">Stem op deze partij</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h3 id="modalTitle">Bevestiging</h3>
            <p id="modalMessage">Weet je het zeker?</p>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="closeModal()">Annuleren</button>
                <button type="button" class="modal-confirm" onclick="submitAction()">Bevestigen</button>
            </div>
        </div>
    </div>

    <!-- Hidden Forms -->
    <form id="voteForm" method="POST" style="display: none;">
        <input type="hidden" name="stem" value="1">
    </form>
    <form id="removeForm" method="POST" style="display: none;">
        <input type="hidden" name="verwijder_stem" value="1">
    </form>

    <script src="./main.js"></script>
    <script>
        let currentAction = null;

        function showConfirmModal(action) {
            currentAction = action;
            const modal = document.getElementById('confirmModal');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            
            if (action === 'vote') {
                title.textContent = 'Stem bevestigen';
                message.textContent = 'Weet je zeker dat je op <?= htmlspecialchars($partij['partij_naam']) ?> wilt stemmen?';
            } else if (action === 'remove') {
                title.textContent = 'Stem verwijderen';
                message.textContent = 'Weet je zeker dat je je stem wilt verwijderen?';
            }
            
            modal.style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
            currentAction = null;
        }

        function submitAction() {
            if (currentAction === 'vote') {
                document.getElementById('voteForm').submit();
            } else if (currentAction === 'remove') {
                document.getElementById('removeForm').submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('confirmModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
    
    <?php if (isset($vote_success)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= addslashes($vote_success) ?>', 'success');
            });
        </script>
    <?php endif; ?>

    <?php if (isset($vote_error)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?= addslashes($vote_error) ?>', 'error');
            });
        </script>
    <?php endif; ?>
</body>
</html>