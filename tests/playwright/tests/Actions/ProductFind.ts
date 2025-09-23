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

import { expect, Page } from '@playwright/test';

export default class ProductFind {
    private productTitle: string = '';
    private page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async openProduct(): Promise<void> {
        await this.page.goto('/');

        const link = this.page.locator('.level0 a').first();
        const url = await link.getAttribute('href');

        console.log('Got URL', url);

        if (url) {
            await this.page.goto(url);
        }

        await expect(await this.page.locator('.product').count()).toBeGreaterThan(1);

        await this.page.locator('.products .product a').first().click();
    }

    async findAndAddToCart(): Promise<void> {
        await this.openProduct();

        const titleElement = await this.page.locator('h1').textContent();
        this.productTitle = titleElement || '';

        await this.page.waitForTimeout(1000);

        await this.page.locator('.options-list input').first().check();

        await this.page.locator('#product-addtocart-button').click();

        await this.page.locator('.counter.qty .counter-number').waitFor({ state: 'visible' });

        await this.page.reload();
    }

    getProductTitle(): string {
        return this.productTitle.trim();
    }
}
