/*
 *     ______            __             __
 *    / ____/___  ____  / /__________  / /
 *   / /   / __ \/ __ \/ __/ ___/ __ \/ /
 *  / /___/ /_/ / / / / /_/ /  / /_/ / /
 *  \______________/_/\__/_/   \____/_/
 *     /   |  / / /_
 *    / /| | / / __/
 *   / ___ |/ / /_
 *  /_/ _|||_/\__/ __     __
 *     / __ \___  / /__  / /____
 *    / / / / _ \/ / _ \/ __/ _ \
 *   / /_/ /  __/ /  __/ /_/  __/
 *  /_____/\___/_/\___/\__/\___/
 *
 * Copyright www.controlaltdelete.dev
 */

import { expect } from '@playwright/test';

export default class ProductFind {
    productTitle = '';

    constructor(page) {
        this.page = page;
    }

    async openProduct() {
        await this.page.goto('/');

        const link = this.page.locator('.level2.category-item a').first();
        const url = await link.getAttribute('href');

        console.log('Got URL', url);
        await this.page.goto(url);

        await expect(await this.page.locator('.product').count()).toBeGreaterThan(1);

        await this.page.locator('.products .product a').first().click();
    }

    async findAndAddToCart() {
        await this.openProduct();

        this.productTitle = await this.page.locator('h1').textContent();

        await this.page.waitForTimeout(1000);

        await expect(this.page.locator('.footer .copyright')).toBeVisible();

        await this.page.locator('#product-addtocart-button').click();

        await expect(this.page.getByText(`U heeft ${this.productTitle.trim()} aan uw winkelwagen toegevoegd.`)).toBeVisible({ timeout: 30 * 1000 });

        await this.page.reload();
    }

    getProductTitle() {
        return this.productTitle.trim();
    }
}
