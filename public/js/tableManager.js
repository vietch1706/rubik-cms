const tableManager = {
    // State
    showFilters: false,
    selectedItems: [],
    selectAll: false,
    sortField: "created_at",
    sortDirection: "desc",
    searchQuery: "",
    deleteRoute: "",
    searchRoute: "",
    itemCount: 0,
    itemIds: [],
    fieldCount: 0,
    entityLower: "",
    entityPlural: "",
    entityPluralLower: "",

    // Initialize the table manager
    init(config) {
        this.deleteRoute = config.deleteRoute;
        this.searchRoute = config.searchRoute;
        this.itemCount = config.itemCount;
        this.itemIds = config.itemIds;
        this.fieldCount = config.fieldCount;
        this.entityLower = config.entityLower;
        this.entityPlural = config.entityPlural;
        this.entityPluralLower = config.entityPluralLower;

        // Bind event listeners
        this.bindEvents();
        this.updateUI();
    },

    // Bind event listeners to DOM elements
    bindEvents() {
        // Prevent checkbox clicks from triggering row navigation
        document.querySelectorAll('input[name="ids"]').forEach((checkbox) => {
            checkbox.addEventListener("click", (e) => e.stopPropagation());
        });

        // Select all checkbox
        const selectAllCheckbox = document.querySelector("#select-all-ids");
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener("change", () => {
                document
                    .querySelectorAll(".checkbox-ids")
                    .forEach((checkbox) => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                this.selectedItems = selectAllCheckbox.checked
                    ? [...this.itemIds]
                    : [];
                this.selectAll = selectAllCheckbox.checked;
                this.updateUI();
            });
        }

        // Individual checkboxes
        document.querySelectorAll(".checkbox-ids").forEach((checkbox) => {
            checkbox.addEventListener("change", () => {
                const id = parseInt(checkbox.value);
                const index = this.selectedItems.indexOf(id);
                if (checkbox.checked && index === -1) {
                    this.selectedItems.push(id);
                } else if (!checkbox.checked && index !== -1) {
                    this.selectedItems.splice(index, 1);
                }
                this.selectAll = this.selectedItems.length === this.itemCount;
                document.querySelector("#select-all-ids").checked =
                    this.selectAll;
                this.updateUI();
            });
        });

        // Delete selected
        const deleteButton = document.querySelector("#delete-record");
        if (deleteButton) {
            deleteButton.addEventListener("click", (e) => {
                e.preventDefault();
                this.deleteSelected();
            });
        }

        // Individual delete buttons
        document.querySelectorAll(".delete-item").forEach((button) => {
            button.addEventListener("click", () => {
                const id = parseInt(button.dataset.id);
                this.deleteSelected(id);
            });
        });

        // Search input
        const searchInput = document.querySelector("#search");
        if (searchInput) {
            let timeout;
            searchInput.addEventListener("keyup", () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.searchQuery = searchInput.value;
                    this.searchItems();
                }, 500); // Debounce 500ms
            });
        }

        // Sort headers
        document.querySelectorAll("th[data-field]").forEach((th) => {
            th.addEventListener("click", () =>
                this.toggleSort(th.dataset.field)
            );
        });

        // Filter toggle
        const filterButton = document.querySelector("#filter-toggle");
        if (filterButton) {
            filterButton.addEventListener("click", () => this.toggleFilters());
        }

        // Apply filters
        const applyFiltersButton = document.querySelector("#apply-filters");
        if (applyFiltersButton) {
            applyFiltersButton.addEventListener("click", () =>
                this.applyFilters()
            );
        }

        // Bulk actions toggle
        const actionsButton = document.querySelector("#actions-toggle");
        if (actionsButton) {
            actionsButton.addEventListener("click", () => this.toggleActions());
            document.addEventListener("click", (e) => {
                if (
                    !actionsButton.contains(e.target) &&
                    !document.querySelector("#actions-menu").contains(e.target)
                ) {
                    this.hideActions();
                }
            });
        }
    },

    // Toggle filters visibility
    toggleFilters() {
        this.showFilters = !this.showFilters;
        this.updateUI();
    },

    // Toggle sort
    toggleSort(field) {
        if (this.sortField === field) {
            this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
        } else {
            this.sortField = field;
            this.sortDirection = "asc";
        }
        this.sortTable();
    },

    // Sort table (client-side)
    sortTable() {
        const rows = Array.from(document.querySelectorAll("tbody tr"));
        const index = Array.from(
            document.querySelector("thead tr").children
        ).findIndex((th) => th.getAttribute("data-field") === this.sortField);
        rows.sort((a, b) => {
            let valA = a.children[index].textContent.trim();
            let valB = b.children[index].textContent.trim();
            if (!isNaN(valA) && !isNaN(valB)) {
                return this.sortDirection === "asc" ? valA - valB : valB - valA;
            }
            return this.sortDirection === "asc"
                ? valA.localeCompare(valB)
                : valB.localeCompare(valA);
        });
        const tbody = document.querySelector("tbody");
        tbody.innerHTML = "";
        rows.forEach((row) => tbody.appendChild(row));
        this.updateSortIcons();
    },

    // Delete selected items
    deleteSelected(id = null) {
        const itemsToDelete = id !== null ? [id] : this.selectedItems;
        if (itemsToDelete.length === 0) {
            Swal.fire({
                icon: "warning",
                title: `No ${this.entityPlural} Selected`,
                text: `Please select at least one ${this.entityLower} to delete.`,
            });
            return;
        }
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this action!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, keep it",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: this.deleteRoute,
                    type: "DELETE",
                    data: {
                        ids: itemsToDelete,
                        _token: document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    success: () => {
                        Swal.fire(
                            "Deleted!",
                            `Your selected ${this.entityPluralLower} have been deleted.`,
                            "success"
                        );
                        itemsToDelete.forEach((id) => {
                            const row = document.querySelector(
                                `#delete-id-${id}`
                            );
                            if (row) row.remove();
                        });
                        this.selectedItems = this.selectedItems.filter(
                            (itemId) => !itemsToDelete.includes(itemId)
                        );
                        this.selectAll = false;
                        this.updateUI();
                    },
                    error: () => {
                        Swal.fire(
                            "Error!",
                            `There was a problem deleting the ${this.entityPluralLower}.`,
                            "error"
                        );
                    },
                });
            }
        });
    },

    // Search items
    searchItems() {
        $.ajax({
            url: this.searchRoute,
            method: "GET",
            data: { search: this.searchQuery },
            success: (response) => {
                if (response.error) {
                    document.querySelector(
                        "tbody"
                    ).innerHTML = `<tr><td colspan="${
                        this.fieldCount + 2
                    }" class="text-latte-red text-center text-lg">${
                        response.error
                    }</td></tr>`;
                    document.querySelector(".pagination-container").innerHTML =
                        "";
                } else {
                    document.querySelector("tbody").innerHTML =
                        response.products;
                    document.querySelector(".pagination-container").innerHTML =
                        response.pagination;
                }
                this.bindEvents(); // Rebind events for new rows
            },
            error: () => {
                Swal.fire(
                    "Error!",
                    `There was a problem searching ${this.entityPluralLower}.`,
                    "error"
                );
            },
        });
    },

    // Apply filters
    applyFilters() {
        const filters = {};
        document.querySelectorAll(".filter-input").forEach((input) => {
            if (input.value) filters[input.id] = input.value;
        });
        $.ajax({
            url: this.searchRoute,
            method: "GET",
            data: { search: this.searchQuery, filters },
            success: (response) => {
                if (response.error) {
                    document.querySelector(
                        "tbody"
                    ).innerHTML = `<tr><td colspan="${
                        this.fieldCount + 2
                    }" class="text-latte-red text-center text-lg">${
                        response.error
                    }</td></tr>`;
                    document.querySelector(".pagination-container").innerHTML =
                        "";
                } else {
                    document.querySelector("tbody").innerHTML =
                        response.products;
                    document.querySelector(".pagination-container").innerHTML =
                        response.pagination;
                }
                this.bindEvents(); // Rebind events for new rows
            },
            error: () => {
                Swal.fire(
                    "Error!",
                    `There was a problem applying filters.`,
                    "error"
                );
            },
        });
    },

    // Toggle actions menu
    toggleActions() {
        const menu = document.querySelector("#actions-menu");
        if (menu) {
            menu.classList.toggle("hidden");
        }
    },

    // Hide actions menu
    hideActions() {
        const menu = document.querySelector("#actions-menu");
        if (menu) {
            menu.classList.add("hidden");
        }
    },

    // Update UI based on state
    updateUI() {
        // Update filter visibility
        const filterSection = document.querySelector("#filter-section");
        if (filterSection) {
            filterSection.classList.toggle("hidden", !this.showFilters);
            const filterIcon = document.querySelector("#filter-icon");
            if (filterIcon) {
                filterIcon.classList.toggle("fa-chevron-up", this.showFilters);
                filterIcon.classList.toggle(
                    "fa-chevron-down",
                    !this.showFilters
                );
            }
        }

        // Update bulk actions visibility and selected count
        const bulkActions = document.querySelector("#bulk-actions");
        if (bulkActions) {
            bulkActions.classList.toggle(
                "hidden",
                this.selectedItems.length === 0
            );
            const selectedCount = document.querySelector("#selected-count");
            if (selectedCount) {
                selectedCount.textContent = this.selectedItems.length;
            }
        }

        // Update sort icons
        this.updateSortIcons();
    },

    // Update sort icons
    updateSortIcons() {
        document.querySelectorAll("th[data-field]").forEach((th) => {
            const icon = th.querySelector("i");
            if (icon) {
                icon.className = "fa-solid ml-1";
                if (th.dataset.field === this.sortField) {
                    icon.classList.add(
                        this.sortDirection === "asc"
                            ? "fa-sort-up"
                            : "fa-sort-down"
                    );
                } else {
                    icon.classList.add("fa-sort");
                }
            }
        });
    },
};

// Initialize on DOM load
document.addEventListener("DOMContentLoaded", () => {
    tableManager.init({
        deleteRoute: document.querySelector('meta[name="delete-route"]')
            .content,
        searchRoute: document.querySelector('meta[name="search-route"]')
            .content,
        itemCount: parseInt(
            document.querySelector('meta[name="item-count"]').content
        ),
        itemIds: JSON.parse(
            document.querySelector('meta[name="item-ids"]').content
        ),
        fieldCount: parseInt(
            document.querySelector('meta[name="field-count"]').content
        ),
        entityLower: document.querySelector('meta[name="entity-lower"]')
            .content,
        entityPluralLower: document.querySelector(
            'meta[name="entity-plural-lower"]'
        ).content,
        entityPlural: document.querySelector('meta[name="entity-plural"]')
            .content,
    });
});
