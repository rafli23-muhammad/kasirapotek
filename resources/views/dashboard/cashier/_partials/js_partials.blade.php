<script>
    window.csrfToken = '{{ csrf_token() }}';
    let cart = [];
    let currentPaymentMethod = 'cash'; // default
    const addSound = new Audio('/sound/beep-29.mp3');
    const checkoutSound = new Audio('/sound/button-21.mp3');

    function playAddSound() {
        addSound.currentTime = 0;
        addSound.play();
    }

    function playCheckoutSound() {
        checkoutSound.currentTime = 0;
        checkoutSound.play();
    }

    function applyStockDeductionFromItems(items) {
        items.forEach(item => {
            const productCard = document.querySelector(`[data-product-id="${item.product_id}"]`);
            if (!productCard) return;

            const stockEl = productCard.querySelector('[data-stock-value]');
            const addButton = productCard.querySelector('[data-add-to-cart]');

            if (!stockEl) return;

            const currentStock = parseInt(stockEl.textContent, 10) || 0;
            const updatedStock = Math.max(0, currentStock - item.quantity);
            stockEl.textContent = updatedStock;

            if (!addButton) return;

            if (updatedStock <= 0) {
                addButton.disabled = true;
                addButton.classList.remove('bg-green-500', 'hover:bg-green-600');
                addButton.classList.add('bg-gray-400');
                addButton.textContent = 'Stok Habis';
            }
        });
    }

    // ✅ Tambah item ke keranjang
    function addToCart(name, price, productId) {
        playAddSound();
        const found = cart.find(item => item.product_id === productId);
        if (found) {
            found.qty++;
        } else {
            const discountRate = {{ $settings->default_discount ?? 0  ?? 0 ?? 0 }};
            const discountPerItem = price * discountRate / 100;
            const discountedPrice = price - discountPerItem;
            cart.push({
                product_id: productId,
                name,
                price_per_item: price,
                qty: 1,
                discount_per_item: discountPerItem,
                subtotal: discountedPrice
            });
        }
        renderCart();
    }

    // ✅ Ubah jumlah
    function changeQty(index, delta) {
        playAddSound();
        cart[index].qty += delta;
        if (cart[index].qty <= 0) cart.splice(index, 1);
        renderCart();
    }

    // ✅ Pembulatan ke ratusan
    function roundDownToHundred(value) {
        return Math.floor(value / 100) * 100;
    }

    // ✅ Render isi keranjang
    function renderCart() {
        const container = document.getElementById('cartItems');
        container.innerHTML = '';
        let subtotal = 0;

        cart.forEach((item, i) => {
            const total = item.price_per_item * item.qty;
            subtotal += total;

            const row = document.createElement('div');
            row.className = 'border-b pb-2';
            row.innerHTML = `
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium">${item.name}</p>
                        <p class="text-sm text-gray-600">Rp ${item.price_per_item.toLocaleString()} x ${item.qty}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="changeQty(${i}, -1)" class="px-2 bg-gray-300 rounded">-</button>
                        <span>${item.qty}</span>
                        <button onclick="changeQty(${i}, 1)" class="px-2 bg-gray-300 rounded">+</button>
                    </div>
                </div>`;
            container.appendChild(row);
        });

        const discountRate = {{ $settings->default_discount ?? 0  ?? 0 ?? 0 }};
        const taxRate = {{ $settings->tax_percentage ?? 0 }};
        const discount = subtotal * discountRate / 100;
        const tax = subtotal * taxRate / 100;
        let total = subtotal - discount + tax;
        total = roundDownToHundred(total);

        document.getElementById('subTotal').textContent = 'Rp ' + subtotal.toLocaleString();
        document.getElementById('discountAmount').textContent = '-Rp ' + discount.toLocaleString();
        document.getElementById('taxAmount').textContent = 'Rp ' + tax.toLocaleString();
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString();
        document.getElementById('payAmount').textContent = 'Rp ' + total.toLocaleString();

        updateChangeAmount();
    }

    // ✅ Update jumlah kembalian
    function updateChangeAmount() {
        const cashInput = document.getElementById('cashInput');
        const changeAmount = document.getElementById('changeAmount');
        const totalText = document.getElementById('payAmount').textContent.replace(/[^\d]/g, '');
        const total = parseInt(totalText) || 0;
        const cash = parseInt(cashInput.value.replace(/[^\d]/g, '')) || 0;
        const change = cash - total;

        if (changeAmount) {
            const formatted = (change < 0 ? '-Rp ' + Math.abs(change).toLocaleString() : 'Rp ' + change.toLocaleString());
            changeAmount.textContent = formatted;
        }
    }

    let nonCashConfirmResolver = null;

    function closeNonCashConfirmModal(isConfirmed) {
        const modal = document.getElementById('nonCashConfirmModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        if (nonCashConfirmResolver) {
            nonCashConfirmResolver(isConfirmed);
            nonCashConfirmResolver = null;
        }
    }

    function showNonCashConfirmModal(amount) {
        return new Promise(resolve => {
            nonCashConfirmResolver = resolve;

            const modal = document.getElementById('nonCashConfirmModal');
            const amountEl = document.getElementById('nonCashConfirmAmount');
            if (amountEl) {
                amountEl.textContent = `Rp ${amount.toLocaleString()}`;
            }

            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                resolve(false);
            }
        });
    }

    function getCategoryNameById(categoryId) {
        const categorySelect = document.getElementById('categoryFilter');
        if (!categorySelect || !categoryId) return '-';

        const categoryOption = Array.from(categorySelect.options).find(option => option.value === categoryId);
        return categoryOption ? categoryOption.textContent.trim() : '-';
    }

    function openProductInfoModal(product) {
        const modal = document.getElementById('productInfoModal');
        const nameEl = document.getElementById('productInfoName');
        const descriptionEl = document.getElementById('productInfoDescription');
        const priceEl = document.getElementById('productInfoPrice');
        const stockEl = document.getElementById('productInfoStock');
        const categoryEl = document.getElementById('productInfoCategory');
        const expiryEl = document.getElementById('productInfoExpiry');

        if (nameEl) {
            nameEl.textContent = product.name || 'Detail Produk';
        }

        if (descriptionEl) {
            descriptionEl.textContent = product.description && product.description.trim() !== ''
                ? product.description
                : 'Deskripsi produk belum tersedia.';
        }

        if (priceEl) {
            const priceNumber = parseInt(product.price, 10) || 0;
            priceEl.textContent = `Rp ${priceNumber.toLocaleString('id-ID')}`;
        }

        if (stockEl) {
            stockEl.textContent = product.stock || '0';
        }

        if (categoryEl) {
            categoryEl.textContent = getCategoryNameById(product.categoryId);
        }

        if (expiryEl) {
            expiryEl.textContent = product.expiry || '-';
        }

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeProductInfoModal() {
        const modal = document.getElementById('productInfoModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // ✅ Checkout
    async function checkout() {
        if (cart.length === 0) {
            return alert('Keranjang kosong');
        }

        let subtotal = 0;
        cart.forEach(item => subtotal += item.price_per_item * item.qty);

        const discountRate = {{ $settings->default_discount ?? 0  ?? 0 ?? 0 }};
        const taxRate = {{ $settings->tax_percentage ?? 0 }};
        const discount_total = subtotal * discountRate / 100;
        const tax = subtotal * taxRate / 100;
        let total = subtotal - discount_total + tax;
        total = roundDownToHundred(total);

        const grand_total = total;

        let cash_received, change;
        if (currentPaymentMethod === 'cash') {
            cash_received = parseInt(document.getElementById('cashInput').value.replace(/[^\d]/g, '')) || 0;
            change = cash_received - grand_total;
            if (cash_received < grand_total) {
                return alert('Uang tunai tidak mencukupi');
            }
        } else {
            cash_received = 0;
            change = 0;
            const isConfirmed = await showNonCashConfirmModal(grand_total);
            if (!isConfirmed) {
                return;
            }
        }

        showLoading();

        const itemsToSend = cart.map(item => ({
            product_id: item.product_id,
            quantity: item.qty,
            price_per_item: item.price_per_item,
            subtotal: item.price_per_item * item.qty
        }));

        playCheckoutSound();

        fetch('/cashier/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({
                items: itemsToSend,
                total: subtotal,
                discount_total: discount_total,
                grand_total: grand_total,
                cash_received: cash_received,
                change: change,
                payment_method: currentPaymentMethod
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (currentPaymentMethod === 'non_cash') {
                    if (!data.snap_token || typeof window.snap === 'undefined') {
                        alert('Gagal membuka pembayaran Midtrans. Pastikan Snap aktif.');
                        hideLoading();
                        return;
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: function() {
                            fetch('/cashier/non-cash/complete', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': window.csrfToken
                                },
                                body: JSON.stringify({
                                    invoice_code: data.invoice_code
                                })
                            })
                            .then(res => res.json())
                            .then(finalizeData => {
                                if (!finalizeData.success) {
                                    alert(finalizeData.message || 'Pembayaran berhasil, tetapi konfirmasi stok gagal.');
                                    return;
                                }

                                applyStockDeductionFromItems(itemsToSend);
                                cart = [];
                                renderCart();
                                document.getElementById('cashInput').value = '';
                                updateChangeAmount();
                                const receiptUrl = finalizeData.receipt_url || data.receipt_url;
                                if (receiptUrl) {
                                    openPrintDialog(receiptUrl);
                                } else {
                                    alert('Pembayaran berhasil, tetapi struk tidak tersedia.');
                                }
                                setTimeout(reloadCashierPage, 800);
                            })
                            .catch(() => {
                                alert('Pembayaran berhasil, tetapi terjadi kesalahan saat konfirmasi stok.');
                            });
                        },
                        onPending: function() {
                            alert('Pembayaran non cash sedang menunggu konfirmasi.');
                        },
                        onError: function() {
                            alert('Pembayaran non cash gagal.');
                        },
                        onClose: function() {
                            alert('Popup pembayaran ditutup sebelum selesai.');
                        }
                    });
                } else {
                    applyStockDeductionFromItems(itemsToSend);
                    cart = [];
                    renderCart();
                    document.getElementById('cashInput').value = '';
                    updateChangeAmount();
                    if (data.receipt_url) {
                        openPrintDialog(data.receipt_url);
                    } else {
                        alert('Transaksi berhasil, tetapi struk tidak tersedia.');
                    }
                    setTimeout(reloadCashierPage, 800);
                }
            } else {
                alert(data.message || 'Gagal checkout');
            }
            hideLoading();
        })
        .catch(err => {
            console.error('Checkout Error:', err);
            alert('Terjadi kesalahan saat checkout');
            hideLoading();
        });
    }

    // ✅ Loading overlay
    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }
    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    }

    // ✅ Tambah uang cepat
    function addCash(amount) {
        const cashInput = document.getElementById('cashInput');
        let currentValue = cashInput.value.replace(/[^\d]/g, '');
        currentValue = parseInt(currentValue) || 0;
        const newValue = currentValue + amount;
        cashInput.value = 'Rp ' + newValue.toLocaleString();
        updateChangeAmount();
    }

    // ✅ Ganti metode pembayaran
    function setPaymentMethod(method) {
        currentPaymentMethod = method;
        const btnCash = document.getElementById('btnCash');
        const btnNonCash = document.getElementById('btnNonCash');
        const selectMoney = document.getElementById('selectMoney');
        const moneyInput = document.getElementById('moneyInput');
        const remainingMoney = document.getElementById('remainingMoney');

        if (btnCash && btnNonCash) {
            if (method === 'cash') {
                btnCash.classList.add('bg-green-500', 'text-white');
                btnCash.classList.remove('bg-gray-200');
                btnNonCash.classList.remove('bg-green-500', 'text-white');
                btnNonCash.classList.add('bg-gray-200');
            } else {
                btnNonCash.classList.add('bg-green-500', 'text-white');
                btnNonCash.classList.remove('bg-gray-200');
                btnCash.classList.remove('bg-green-500', 'text-white');
                btnCash.classList.add('bg-gray-200');
            }
        }

        const isCash = method === 'cash';
        if (selectMoney) selectMoney.style.display = isCash ? 'block' : 'none';
        if (moneyInput) moneyInput.style.display = isCash ? 'block' : 'none';
        if (remainingMoney) remainingMoney.style.display = isCash ? 'block' : 'none';
    }

    // ✅ Print struk
    function normalizeReceiptUrl(url) {
        if (url === null || url === undefined) {
            return null;
        }

        const normalized = String(url).trim();
        if (!normalized || normalized === 'null' || normalized === 'undefined') {
            return null;
        }

        return normalized;
    }

    function openPrintDialog(url) {
        const receiptUrl = normalizeReceiptUrl(url);
        if (!receiptUrl) {
            alert('Struk tidak tersedia.');
            return;
        }

        const printWindow = window.open(receiptUrl, '_blank');
        if (printWindow) {
            printWindow.focus();
        } else {
            alert('Izinkan pop-up untuk mencetak struk.');
        }
    }

    function reloadCashierPage() {
        window.location.reload();
    }

    function updateClock() {
        const clockEl = document.getElementById('clock');
        if (!clockEl) return;

        const now = new Date();
        clockEl.textContent = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        });
    }

    function filterProducts() {
        const searchEl = document.getElementById('search');
        const categoryEl = document.getElementById('categoryFilter');
        const cards = document.querySelectorAll('#productList [data-product-id]');

        if (!searchEl || !categoryEl || cards.length === 0) return;

        const searchText = searchEl.value.trim().toLowerCase();
        const selectedCategory = categoryEl.value;

        cards.forEach(card => {
            const productName = card.dataset.productName || '';
            const categoryId = card.dataset.categoryId || '';

            const matchSearch = productName.includes(searchText);
            const matchCategory = selectedCategory === 'all' || categoryId === selectedCategory;
            card.style.display = matchSearch && matchCategory ? '' : 'none';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const cashInput = document.getElementById('cashInput');
        const nonCashOkBtn = document.getElementById('nonCashOkBtn');
        const nonCashCancelBtn = document.getElementById('nonCashCancelBtn');
        const nonCashModal = document.getElementById('nonCashConfirmModal');
        const productInfoModal = document.getElementById('productInfoModal');
        const productInfoCloseBtn = document.getElementById('productInfoCloseBtn');
        const searchInput = document.getElementById('search');
        const categoryFilter = document.getElementById('categoryFilter');
        const productInfoTriggers = document.querySelectorAll('[data-product-info-trigger]');

        if (cashInput) {
            cashInput.addEventListener('input', updateChangeAmount);
        }

        if (nonCashOkBtn) {
            nonCashOkBtn.addEventListener('click', function() {
                closeNonCashConfirmModal(true);
            });
        }

        if (nonCashCancelBtn) {
            nonCashCancelBtn.addEventListener('click', function() {
                closeNonCashConfirmModal(false);
            });
        }

        if (nonCashModal) {
            nonCashModal.addEventListener('click', function(event) {
                if (event.target === nonCashModal) {
                    closeNonCashConfirmModal(false);
                }
            });
        }

        if (productInfoCloseBtn) {
            productInfoCloseBtn.addEventListener('click', closeProductInfoModal);
        }

        if (productInfoModal) {
            productInfoModal.addEventListener('click', function(event) {
                if (event.target === productInfoModal) {
                    closeProductInfoModal();
                }
            });
        }

        productInfoTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const productCard = trigger.closest('[data-product-id]');
                if (!productCard) return;

                const productName = productCard.dataset.productDisplayName
                    || 'Produk';
                const productData = {
                    name: productName,
                    description: productCard.dataset.productDescription || '',
                    price: productCard.dataset.productPrice || '0',
                    stock: productCard.dataset.productStock || '0',
                    categoryId: productCard.dataset.categoryId || '',
                    expiry: productCard.dataset.productExpiry || '-',
                };

                openProductInfoModal(productData);
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', filterProducts);
        }

        if (categoryFilter) {
            categoryFilter.addEventListener('change', filterProducts);
        }

        setPaymentMethod('cash');
        filterProducts();
        updateClock();
        setInterval(updateClock, 1000);
    });
</script>
