<!-- WhatsApp Floating Button -->
<a href="https://api.whatsapp.com/send?phone=<?= !empty($contactSettings['whatsapp_number']) ? htmlspecialchars($contactSettings['whatsapp_number']) : '+91-9819866348' ?>&text=Hello%21%20."
    class="floating" target="_blank">

    <i class="fa-brands fa-whatsapp float-button"></i>
</a>

<?php include 'common-js.php' ?>
</body>

</html>