</div> <!-- .container -->
<script src="js/main.js"></script>
<script>
    // Update cart count on every page
    fetch('index.php?controller=cart&action=getCountAjax')
        .then(res => res.json())
        .then(data => {
            let span = document.getElementById('cartCount');
            if (span && data.cart_count) span.innerText = data.cart_count;
        })
        .catch(() => {});
</script>
</body>
</html>