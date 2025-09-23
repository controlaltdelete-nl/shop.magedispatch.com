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
import Checkout from './Actions/Checkout';

test('Can enter shipping address on Checkout', async ({ page }) => {
    await new Checkout(page).visit();

    await page.locator('#customer-email-fieldset').getByLabel('Email Address').fill('user@example.com');

    const shipping = page.locator('#shipping');
    await shipping.getByLabel('First Name').fill('John');
    await shipping.getByLabel('Last Name').fill('Doe');
    await shipping.getByLabel('Street Address').first().fill('Kikkertstraat');
    await shipping.getByLabel('Zip/Postal Code').fill('1795AD');
    await shipping.getByLabel('City').fill('De Cocksdorp');
    await shipping.getByLabel('Phone Number').fill('111111111111');

    await page.locator('.table-checkout-shipping-method input').first().click();

    await page.getByRole('button', { name: 'Next' }).click();

    await page.locator('.loading-mask').first().waitFor({ state: 'visible' });
    await page.locator('.loading-mask').first().waitFor({ state: 'hidden' });

    await page.locator('.payment-method-title label').first().click();

    await expect(page.getByText('Place Order').first()).toBeVisible();
});
