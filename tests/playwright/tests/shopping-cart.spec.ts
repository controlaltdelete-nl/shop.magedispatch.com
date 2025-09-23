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

import { test, expect } from '@playwright/test';
import ProductFindComponent from './Actions/ProductFind';

test('Can remove product from cart', async ({ page }) => {
    const ProductFind = new ProductFindComponent(page);

    await ProductFind.findAndAddToCart();

    const productTitle = ProductFind.getProductTitle();

    await page.goto('/checkout/cart');

    await expect(page.locator('#shopping-cart-table').getByText(productTitle)).toBeVisible();

    await page.locator('.action-delete').click();

    await expect(page.getByText(productTitle)).not.toBeVisible();

    await expect(page.getByText('You have no items in your shopping cart.')).toBeVisible();
});

test.skip('Can change the quantity in the cart', async ({ page }) => {
    const ProductFind = new ProductFindComponent(page);

    await ProductFind.findAndAddToCart();

    await page.goto('/checkout/cart');

    await expect(await page.locator('input.qty').count()).toBe(1);
    await expect(await page.locator('input.qty')).toHaveValue('1');

    const priceWithoutTax = await page.locator('.col.subtotal .price').textContent();

    await page.locator('input.qty').fill('2');

    await page.getByRole('button', { name: 'Update Shopping Cart' }).click();

    await expect(await page.locator('.loader')).not.toBeVisible();

    const updatedPrice = await page.locator('.col.subtotal .price').textContent();
    console.log('priceWithoutTax', priceWithoutTax);
    console.log('updatedPrice', updatedPrice);

    expect(updatedPrice).not.toEqual(priceWithoutTax);
});