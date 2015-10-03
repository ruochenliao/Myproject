package com.example.john.myfirstapp;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import org.w3c.dom.Text;

import java.io.InputStream;


public class View_detail extends ActionBarActivity {
    public class DownloadImageTask extends AsyncTask<String, Void, Bitmap> {
        ImageView bmImage;

        public DownloadImageTask(ImageView bmImage) {
            this.bmImage = bmImage;
        }

        protected Bitmap doInBackground(String... urls) {
            String urldisplay = urls[0];
            Bitmap mIcon11 = null;
            try {
                InputStream in = new java.net.URL(urldisplay).openStream();
                mIcon11 = BitmapFactory.decodeStream(in);
            } catch (Exception e) {
                Log.e("Error", e.getMessage());
                e.printStackTrace();
            }
            return mIcon11;
        }

        protected void onPostExecute(Bitmap result) {
            //Log.i("debug","display image");
            bmImage.setImageBitmap(result);
        }
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_view_detail);
        Bundle bundle;
        Intent intent =this.getIntent();
        bundle = intent.getExtras();
        String title = bundle.getString("title");
        String image_url = bundle.getString("image_url");
        String price = bundle.getString("price");
        String ship_cost = bundle.getString("ship_cost");
        String item_location = bundle.getString("item_location");
        final String item_url = bundle.getString("item_url");
        String top_rated_item = bundle.getString("top_rated_item");
        //basic info
        String categoryName = bundle.getString("categoryName");Log.i("hello",categoryName);
        String condition = bundle.getString("condition");Log.i("hello",condition);
        String buying_format = bundle.getString("buying_format");Log.i("hello",buying_format);

        TextView categoryName_s = (TextView)findViewById(R.id.category_name_s);
        categoryName_s.setText(categoryName);
        //seller
        String user_name = bundle.getString("user_name");Log.i("hello",user_name);
        String feedback_score = bundle.getString("feedback_score");Log.i("hello",feedback_score);
        String positive_feedback = bundle.getString("positive_feedback");Log.i("hello",positive_feedback);
        String top_rated_seller = bundle.getString("top_rated_seller");Log.i("hello",top_rated_seller);
        String feedback_rating = bundle.getString("feedback_rating");Log.i("hello",feedback_rating);
        //shipping
        String shipping_type = bundle.getString("shipping_type");Log.i("hello",shipping_type);
        String handling_time = bundle.getString("handling_time");Log.i("hello",handling_time);
        String shipping_loactions = bundle.getString("shipping_loactions");Log.i("hello",shipping_loactions);
        String expedited_shipping = bundle.getString("expedited_shipping");Log.i("hello",expedited_shipping);
        String one_day_shipping = bundle.getString("one_day_shipping");Log.i("hello",one_day_shipping);
        String return_accepted = bundle.getString("return_accepted");Log.i("hello",return_accepted);

        ImageView image_view = (ImageView)findViewById(R.id.image0);
        DownloadImageTask download_img = new DownloadImageTask(image_view);
        download_img.execute(image_url);

        TextView show_title = (TextView)findViewById(R.id.title);
        TextView show_price = (TextView)findViewById(R.id.price);
        TextView show_item_location = (TextView)findViewById(R.id.item_location);
        ImageView top_rated_img = (ImageView)findViewById(R.id.top_rated);
        ImageView facebook_icon = (ImageView)findViewById(R.id.facebook_icon);

        Button show_buy_now = (Button)findViewById(R.id.buy_now);
        show_title.setText(title);
        show_price.setText( "Price:$"+price+"("+ship_cost+" Shipping)" );
        show_item_location.setText(item_location);
        facebook_icon.setImageResource(R.drawable.fb);
        if( top_rated_item.equals("true") ) {
            top_rated_img.setImageResource(R.drawable.toprated);
        }
        show_buy_now.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(item_url));
                startActivity(browserIntent);
            }
        });
        // show detail of basic info button
        Button basic_info =(Button)findViewById(R.id.basic_info);
        basic_info.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                RelativeLayout rl1 = (RelativeLayout) findViewById(R.id.seller_layout);
                rl1.setVisibility(View.INVISIBLE);
                RelativeLayout rl3 = (RelativeLayout) findViewById(R.id.shipping_layout);
                rl3.setVisibility(View.INVISIBLE);
                RelativeLayout rl2 = (RelativeLayout) findViewById(R.id.basic_info_layout);
                rl2.setVisibility(View.VISIBLE);


            }
        });
        //show detail of seller button
        Button seller =(Button)findViewById(R.id.seller);
        seller.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                RelativeLayout r21 = (RelativeLayout) findViewById(R.id.seller_layout);
                r21.setVisibility(View.VISIBLE);
                RelativeLayout r23 = (RelativeLayout) findViewById(R.id.shipping_layout);
                r23.setVisibility(View.INVISIBLE);
                RelativeLayout r22 = (RelativeLayout) findViewById(R.id.basic_info_layout);
                r22.setVisibility(View.INVISIBLE);
            }
        });
        //show detail of shipping button
        Button shipping =(Button)findViewById(R.id.shipping);
        shipping.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                RelativeLayout r31 = (RelativeLayout) findViewById(R.id.seller_layout);
                r31.setVisibility(View.INVISIBLE);
                RelativeLayout r33 = (RelativeLayout) findViewById(R.id.shipping_layout);
                r33.setVisibility(View.VISIBLE);
                RelativeLayout r32 = (RelativeLayout) findViewById(R.id.basic_info_layout);
                r32.setVisibility(View.INVISIBLE);
            }
        });
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_view_detail, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }
}
