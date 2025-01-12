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
import Checkout from "./Actions/Checkout";

test('Can enter shipping address on Checkout', async ({ page }) => {
    await new Checkout(page).visit();

    await page.locator('#guest_details').getByLabel('Email address').fill('user@example.com');

    const shipping = page.locator('#shipping-details');
    await shipping.getByLabel('First Name').fill('John');
    await shipping.getByLabel('Last Name').fill('Doe');
    await shipping.getByLabel('Street Address').fill('Kikkertstraat');
    await shipping.getByLabel('Zip/Postal Code').fill('1795AD');
    await shipping.getByLabel('City').fill('De Cocksdorp');
    await shipping.getByLabel('Phone Number').fill('111111111111');

    await page.locator('[name="shipping-method-option"]').first().click();

    await page.locator('[name="payment-method-option"]').first().click();

    await expect(page.getByText('Place Order').first()).toBeVisible();
});
